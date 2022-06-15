<?php
/*
 +---------------------------------------------------------------------------+
 | Copyright (c) 2013-2016, Ramon Kayo                                       |
 +---------------------------------------------------------------------------+
 | Author        : Ramon Kayo                                                |
 | Email         : contato@ramonk.com.                                       |
 | License       : Distributed under the MIT License                         |
 | Full license  : https://github.com/ramokcom/simple-upload-handler         |
 +---------------------------------------------------------------------------+
 | "Simplicity is the ultimate sophistication." - Leonardo Da Vinci          |
 +---------------------------------------------------------------------------+
*/

namespace RamonK\SimpleUploadHandler;

class File {
	
	public
		$baseName,
		$directory,
		$error,
		$extension,
		$fileName,
		$size,
		$temporaryName,
		$type;
}