<?php
/**
 * Import File Parser
 *
 * Reads .xlsx and .csv source files and returns a uniform array of rows,
 * each represented as an associative array keyed by the spreadsheet's
 * header row values.
 *
 * Dynamic header detection: column positions are never hardcoded.
 * The first non-empty row is treated as the header row.
 *
 * Supported file types:
 *   - .xlsx --- via PhpSpreadsheet (installed by Composer)
 *   - .csv  --- via native PHP fgetcsv()
 *
 * @package ITHS\Import
 */

namespace ITHS\Import;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as SpreadsheetDate;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Parser {

	/** @var string Path to the upload file on disk. */
	private string $file_path;

	/** @var string 'xlsx' or 'csv'. */
	private string $file_type;

	/** @var string[] Parsed header row values (in column order). */
	private array $headers = array();

	/** @var array[] Parsed data rows as associative arrays. */
	private array $rows = array();

	/** @var string[] Parse error messages. */
	private array $errors = array();

	/**
	 * @param string $file_path Full filesystem path to the uploaded file.
	 * @param string $file_type File type: 'xlsx' or 'csv'.
	 */
	public function __construct( string $file_path, string $file_type ) {
		$this->file_path = $file_path;
		$this->file_type = strtolower( $file_type );
	}

	// ------ Public API ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Parses the file and populates $this->rows.
	 *
	 * @return bool True on success, false if parsing failed.
	 */
	public function parse(): bool {
		if ( ! file_exists( $this->file_path ) ) {
			$this->errors[] = 'File not found: ' . $this->file_path;
			return false;
		}

		try {
			if ( 'xlsx' === $this->file_type ) {
				return $this->parse_xlsx();
			} elseif ( 'csv' === $this->file_type ) {
				return $this->parse_csv();
			} else {
				$this->errors[] = 'Unsupported file type: ' . $this->file_type . '. Only xlsx and csv are accepted.';
				return false;
			}
		} catch ( \Throwable $e ) {
			$this->errors[] = 'Parse error: ' . $e->getMessage();
			return false;
		}
	}

	/**
	 * Returns all parsed rows as associative arrays (header => value).
	 * Rows where the Content Status column starts with 'Duplicate' are included
	 * (the Validator handles skip logic --- the Parser is format-agnostic).
	 *
	 * @return array[]
	 */
	public function get_rows(): array {
		return $this->rows;
	}

	/**
	 * Returns the detected column headers.
	 *
	 * @return string[]
	 */
	public function get_headers(): array {
		return $this->headers;
	}

	/**
	 * Returns any errors encountered during parsing.
	 *
	 * @return string[]
	 */
	public function get_errors(): array {
		return $this->errors;
	}

	/**
	 * Returns the number of parsed data rows (excluding the header row).
	 *
	 * @return int
	 */
	public function count(): int {
		return count( $this->rows );
	}

	// ------ xlsx Parsing ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Parses the xlsx file using PhpSpreadsheet.
	 *
	 * Reads only the first worksheet. Stops at the first fully empty row.
	 * Skips completely blank rows silently.
	 *
	 * @return bool
	 */
	private function parse_xlsx(): bool {
		// PhpSpreadsheet autoloader is loaded by ImportServiceProvider.
		if ( ! class_exists( '\PhpOffice\PhpSpreadsheet\IOFactory' ) ) {
			$this->errors[] = 'PhpSpreadsheet is not available. Run composer install in the theme directory.';
			return false;
		}

		$reader = IOFactory::createReaderForFile( $this->file_path );
		$reader->setReadDataOnly( true );

		$spreadsheet = $reader->load( $this->file_path );
		$worksheet   = $spreadsheet->getActiveSheet();

		$row_iterator = $worksheet->getRowIterator();
		$header_found = false;

		foreach ( $row_iterator as $row ) {
			$cell_iterator = $row->getCellIterator();
			$cell_iterator->setIterateOnlyExistingCells( false );

			$raw_row = array();
			foreach ( $cell_iterator as $cell ) {
				$value = $cell->getValue();

				// Handle Excel date serials.
				if ( $cell->getDataType() === \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC
					&& SpreadsheetDate::isDateTime( $cell )
				) {
					$value = SpreadsheetDate::excelToDateTimeObject( $value )->format( 'Y-m-d' );
				}

				$raw_row[] = ( null === $value ) ? '' : trim( (string) $value );
			}

			// Skip completely blank rows.
			$non_empty = array_filter( $raw_row, fn( $v ) => '' !== $v );
			if ( empty( $non_empty ) ) {
				if ( $header_found ) {
					break; // Stop at first fully empty row after data begins.
				}
				continue;
			}

			if ( ! $header_found ) {
				$this->headers = $raw_row;
				$header_found  = true;
				continue;
			}

			// Pad row to header length and combine.
			$raw_row = array_pad( $raw_row, count( $this->headers ), '' );
			$row_assoc = array_combine( $this->headers, array_slice( $raw_row, 0, count( $this->headers ) ) );

			if ( false !== $row_assoc ) {
				$this->rows[] = $row_assoc;
			}
		}

		$spreadsheet->disconnectWorksheets();
		unset( $spreadsheet );

		if ( empty( $this->headers ) ) {
			$this->errors[] = 'No header row found in the xlsx file.';
			return false;
		}

		return true;
	}

	// ------ CSV Parsing ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Parses the CSV file using native PHP fgetcsv().
	 *
	 * Auto-detects delimiter (comma, semicolon, tab) by testing the header row.
	 * Handles BOM (byte-order mark) in UTF-8 CSV files.
	 *
	 * @return bool
	 */
	private function parse_csv(): bool {
		$handle = fopen( $this->file_path, 'r' );
		if ( ! $handle ) {
			$this->errors[] = 'Could not open CSV file for reading.';
			return false;
		}

		// Strip UTF-8 BOM if present.
		$bom = fread( $handle, 3 );
		if ( "\xEF\xBB\xBF" !== $bom ) {
			rewind( $handle );
		}

		// Auto-detect delimiter from first line.
		$first_line = fgets( $handle );
		rewind( $handle );
		if ( "\xEF\xBB\xBF" === substr( $first_line, 0, 3 ) ) {
			fread( $handle, 3 );
		}

		$delimiter = $this->detect_csv_delimiter( $first_line );

		$header_found = false;

		while ( ! feof( $handle ) ) {
			$row = fgetcsv( $handle, 0, $delimiter );
			if ( false === $row ) {
				continue;
			}

			$row = array_map( fn( $v ) => trim( (string) $v ), $row );

			// Skip completely blank rows.
			if ( empty( array_filter( $row, fn( $v ) => '' !== $v ) ) ) {
				continue;
			}

			if ( ! $header_found ) {
				$this->headers = $row;
				$header_found  = true;
				continue;
			}

			$row       = array_pad( $row, count( $this->headers ), '' );
			$row_assoc = array_combine( $this->headers, array_slice( $row, 0, count( $this->headers ) ) );

			if ( false !== $row_assoc ) {
				$this->rows[] = $row_assoc;
			}
		}

		fclose( $handle );

		if ( empty( $this->headers ) ) {
			$this->errors[] = 'No header row found in the CSV file.';
			return false;
		}

		return true;
	}

	/**
	 * Detects the delimiter used in a CSV line.
	 *
	 * @param string $line First line of the CSV file.
	 * @return string Delimiter character.
	 */
	private function detect_csv_delimiter( string $line ): string {
		$candidates = array( ',', ';', "\t", '|' );
		$counts     = array();

		foreach ( $candidates as $d ) {
			$counts[ $d ] = substr_count( $line, $d );
		}

		arsort( $counts );
		$best = array_key_first( $counts );

		return ( $counts[ $best ] > 0 ) ? $best : ',';
	}
}

