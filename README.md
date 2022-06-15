# SimpleUploadHandler

SimpleUploadHandler wraps up file uploading processes.

## Basic Usage

### Installation

Install the latest version with:

```bash
$ composer require ramonztro/simple-upload-handler
```

### Basic Usage

```php
<?php

use RamonK\SimpleUploadHandler\SimpleUploadHandler;

//Creates a SimpleUploadHandler
$handler = new SimpleUploadHandler();

$i = 0;

//Navigates through files
while($handler->haveFiles()) {
    try {
        //Checks extension and errors
        $handler->checkFile('jpg', 'jpeg', 'gif', 'png');
    } catch (Exception $e) {
        //Handles errors
        die($e->getMessage());
    }
    try {
        //Move current file to a directory
        //(File extension is kept)
        $handler->move('directory/wanted/', 'optional_new_name_' . $i++);

        //Displays full filename (directory + basename)
        echo $handler->getFileName();
        
        //Displays basename (no directory path)
        echo $handler->getBaseName();
        
        //Displays other available information
        echo $handler->getDirectory();
        echo $handler->getExtension();
        echo $handler->getSize();
        echo $handler->getType();
    } catch (Exception $e) {
        //Handles errors
        die($e->getMessage());
    }
}

```

## About

### Requirements

- PHP 5.3 or higher.

### MIT License

*Permission is hereby granted, free of charge, to any person obtaining a copy * 
of this software and associated documentation files (the "Software"), to    
deal in the Software without restriction, including without limitation the  
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or 
sell copies of the Software, and to permit persons to whom the Software is  
furnished to do so, subject to the following conditions:*                    
                                                                            
*The above copyright notice and this permission notice shall be included in  
all copies or substantial portions of the Software.*                         
                                                                            
*THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR  
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,    
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER      
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING     
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS * 
IN THE SOFTWARE.*

### Author

Ramon Kayo - <contato@ramonk.com>
