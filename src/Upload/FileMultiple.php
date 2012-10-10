<?php
/**
 * Upload
 *
 * @author      Adam Brett <adam@adambrett.co.uk>
 * @copyright   2012 Adam Brett
 * @link        http://github.com/adambrett
 * @version     1.0.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Upload;

/**
 * FileMultiple
 *
 * This class provides a consistent interface for multiple file uploads.
 *
 * @author  Adam Brett <adam@adambrett.co.uk>
 * @since   1.0.0
 * @package Upload
 * @todo    Add interface
 */
class FileMultiple
{
    /********************************************************************************
    * Instance Properties
    *******************************************************************************/

    /**
     * Files
     * @var \Upload\File
     */
    protected $files;

    /**
     * Constructor
     * @param  string                            $key            The file's key in $_FILES superglobal
     * @param  \Upload\Storage\Base              $storage        The method with which to store file
     * @throws \Upload\Exception\UploadException If file uploads are disabled in the php.ini file
     * @throws \InvalidArgumentException         If $_FILES key does not exist
     */
    public function __construct($key, \Upload\Storage\Base $storage)
    {
        if (!is_array($_FILES[$key]['name'])) {
            return new File($key, $storage);
        }

        $files = $_FILES;
        $_FILES = array();
        foreach ($files[$key]['name'] as $index => $value) {
            foreach ($files[$key] as $field => $array) {
                $_FILES[$key . '_' . $index][$field] = $array[$index];
            }

            $this->files[] = new File($key . '_' . $index, $storage);
        }
    }

    /********************************************************************************
    * Validate
    *******************************************************************************/

    /**
     * Add file validations
     * @param \Upload\Validation\Base|array[\Upload\Validation\Base] $validations
     */
    public function addValidations($validations)
    {
        foreach ($this->files as &$file) {
            $file->addValidations($validations);
        }
    }

    /**
     * Get file validations
     * @return array[\Upload\Validation\Base]
     */
    public function getValidations()
    {
        return $this->files[0]->getValidations();
    }

    /**
     * Validate file
     * @return bool True if valid, false if invalid
     */
    public function validate()
    {
        foreach ($this->files as $file) {
            if (!$file->validate()) {
                return false;
            }
        }

        return true;
    }


    /********************************************************************************
    * Upload
    *******************************************************************************/

    /**
     * Upload file (delegated to storage object)
     * @return bool
     * @throws \Upload\Exception\UploadException If file does not validate
     */
    public function upload()
    {
        foreach ($this->files as $file) {
            if (!$file->upload()) {
                return false;
            }
        }

        return true;
    }
}