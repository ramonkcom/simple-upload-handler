<?php
/*
 +---------------------------------------------------------------------------+
 | Copyright (c) 2013-2016, Ramon Kayo                                       |
 +---------------------------------------------------------------------------+
 | Author        : Ramon Kayo                                                |
 | Email         : contato@ramonk.com.                                       |
 | License       : Distributed under the MIT License                         |
 | Full license  : https://github.com/ramonkcom/simple-upload-handler        |
 +---------------------------------------------------------------------------+
 | "Simplicity is the ultimate sophistication." - Leonardo Da Vinci          |
 +---------------------------------------------------------------------------+
*/

namespace RamonK\SimpleUploadHandler;

class SimpleUploadHandler {
	
	private 
		$files = array(),
		$currentFile = null,
		$currentIndex = -1;
	
	
/*===========================================================================*/
// CONSTRUCTOR
/*===========================================================================*/
	/**
	 * 
	 */
	public function __construct() {
		foreach ($_FILES as $file) {
			if (!empty($file['tmp_name'])) {
				$this->prepareFiles();
				break;
			}
		}
	}
	
	
/*===========================================================================*/
// PUBLIC METHODS
/*===========================================================================*/
	/**
	 *
	 * @param array $allowedExtensions
	 * @throws Exception
	 */
	public function checkFile() {
		$allowedExtensions = array();
		foreach (func_get_args() as $extension) {
			$allowedExtensions[] = $extension;
		}
		if (!is_uploaded_file($this->currentFile->temporaryName)) {
			throw new Exception($this->translateErrorMessage($this->currentFile->error));
		} else {
			if (
				!empty($allowedExtensions) &&
				!in_array(strtoupper($this->currentFile->extension), $allowedExtensions) &&
				!in_array(strtolower($this->currentFile->extension), $allowedExtensions)
			)
				throw new Exception(
					str_replace(
						'{extensions}', 
						implode(', ', $allowedExtensions), 
						'Invalid file extension. File extension should be one of the following: {extensions}.'
					)
				);
			if ($this->currentFile->error > 0) {
				throw new Exception(
					$this->translateErrorMessage($this->currentFile->error)
				);
			}
		}
	}
	
	/**
	 *
	 * @param string $name
	 * @return bool
	 */
	public function fileExists($key) {
		foreach ($_FILES as $name => $file)
			if ($name == $key && !empty($file['tmp_name'])) return true;
		return false;
	}

	/**
	 * 
	 * @return string
	 */
	public function getBaseName() { 
		if (is_null($this->currentFile)) return null; 
		return $this->currentFile->baseName; 
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getDirectory() { 
		if (is_null($this->currentFile)) return null; 
		return $this->currentFile->directory; 
	}

	/**
	 * 
	 * @return string
	 */
	public function getError() { 
		if (is_null($this->currentFile)) return null; 
		return $this->currentFile->error; 
	}

	/**
	 * 
	 * @return string
	 */
	public function getErrorMessage() { 
		if (is_null($this->currentFile)) return null; 
		return $this->translateErrorMessage($this->currentFile->error); 
	}
	

	/**
	 * 
	 * @return string
	 */
	public function getExtension() { 
		if (is_null($this->currentFile)) return null; 
		return $this->currentFile->extension; 
	}
	

	/**
	 * 
	 * @return string
	 */
	public function getFileName() { 
		if (is_null($this->currentFile)) return null; 
		return $this->currentFile->fileName; 
	}
	

	/**
	 * 
	 * @return int
	 */
	public function getSize() { 
		if (is_null($this->currentFile)) return null; 
		return $this->currentFile->size; 
	}
	

	/**
	 * 
	 * @return string
	 */
	public function getTemporaryName() { 
		if (is_null($this->currentFile)) return null; 
		return $this->currentFile->temporaryName; 
	}
	

	/**
	 * 
	 * @return string
	 */
	public function getType() { 
		if (is_null($this->currentFile)) return null; 
		return $this->currentFile->type; 
	}

	/**
	 * @return bool
	 */
	public function haveFiles() {
		if (empty($this->files)) return false;
		if (isset($this->files[$this->currentIndex + 1])) {
			$this->currentIndex++;
			$this->currentFile = $this->files[$this->currentIndex];
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * @param string $dir
	 * @param string $name
	 * @return bool
	 * @throws Exception
	 */
	public function move($dir = '', $name = null) {
		if (is_null($this->currentFile) && count($this->files) == 1)
			$this->currentFile == $this->files[0];
	
		if (substr($dir, -1) != '/') $dir .= '/';
		
		$this->currentFile->directory = $dir;
		$this->currentFile->baseName = is_null($name) ? $this->currentFile->baseName : $name;
		if (strpos($this->currentFile->baseName, $this->currentFile->extension) === false) {
			$this->currentFile->baseName = "{$this->currentFile->baseName}.{$this->currentFile->extension}";
		}
		$this->currentFile->fileName = $this->currentFile->directory . $this->currentFile->baseName;
		
		if (!move_uploaded_file($this->currentFile->temporaryName, $this->currentFile->fileName)) 
			throw new Exception("Couldn't move file.");
		
		return true;
	}
	
	
/*===========================================================================*/
// PRIVATE METHODS
/*===========================================================================*/
	private function prepareFiles() {
		foreach ($_FILES as $name => $file) {
			if ($file['error'] != UPLOAD_ERR_OK)
				throw new Exception($this->translateErrorMessage($file['error']));
			if (empty($file['tmp_name'])) continue;
			if (!is_array($file['tmp_name'])) {
				$newFile = new File();
				$newFile->extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
				$newFile->baseName = $file['name'];
				$newFile->type = $file['type'];
				$newFile->size = $file['size'];
				$newFile->temporaryName = $file['tmp_name'];
				$newFile->error = $file['error'];
				$this->files[] = $newFile;
				$this->currentFile = $newFile;
			} else if (is_array($file['tmp_name'])) {
				$top = count($file['tmp_name']);
				for ($i=0; $i<$top; $i++) {
					$newFile = new File();
					$newFile->extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
					$newFile->baseName = $file['name'];
					$newFile->type = $file['type'];
					$newFile->size = $file['size'];
					$newFile->temporaryName = $file['tmp_name'];
					$newFile->error = $file['error'];
					$this->files[] = $newFile;
					if ($i == 0) {
						$this->currentFile = $newFile;
					}
				}
			}
		}
	}

	/**
	 *
	 * @param int $errorCode
	 */
	private function translateErrorMessage($errorCode) {
		switch ($errorCode) {
			case UPLOAD_ERR_INI_SIZE:
				return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			case UPLOAD_ERR_FORM_SIZE:
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			case UPLOAD_ERR_PARTIAL:
				return 'The uploaded file was only partially uploaded';
			case UPLOAD_ERR_NO_FILE:
				return 'No file was uploaded';
			case UPLOAD_ERR_NO_TMP_DIR:
				return 'Missing a temporary folder';
			case UPLOAD_ERR_CANT_WRITE:
				return 'Failed to write file to disk';
			case UPLOAD_ERR_EXTENSION:
				return 'File upload stopped by extension';
			default:
				return 'Unknown upload error';
		}
	}

}