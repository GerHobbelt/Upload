# Upload

## Usage

**USE THIS FORK AT YOUR OWN RISK.  THIS IS A QUICK HACK TO ENABLE MULTIPLE-FILE UPLOADS FOR A DISPOSABLE
PERSONAL PROJECT.  IT IS NOT UNIT TESTED, IT IS HACKY AND I MAKE NO CLAIMS THAT IT EVEN WORKS CORRECTLY.**

**ONCE MULTIPLE FILE UPLOADS ARE ADDED TO THE UPSTREAM OR A SIMILAR LIBRARY THIS FORK WILL BE DISCONTINUED.**

This component simplifies file validation and uploading. Assume a file is uploaded with this HTML form:

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="foo[]" value=""/ multiple>
        <input type="submit" value="Upload File"/>
    </form>

When the HTML form is submitted, the server-side PHP code can validate and upload the file like this:

    <?php
    $storage = new \Upload\Storage\FileSystem('/path/to/directory');
    $file = new \Upload\FileMultiple('foo', $storage);

    // Validate file upload
    $file->addValidations(array(
        // Ensure file is of type "image/png"
        new \Upload\Validation\Mimetype('image/png'),

        // Ensure file is no larger than 5M (use "B", "K", M", or "G")
        new \Upload\Validation\Size('5M')
    ));

    // Try to upload file
    try {
        // Success!
        $file->upload();
    } catch (\Exception $e) {
        // Fail!
        $errors = $file->getErrors();
    }

## How to Install

Install composer in your project:

    curl -s https://getcomposer.org/installer | php

Create a composer.json file in your project root:

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "http://github.com/adambrett/Upload"
            }
        ],
        "require": {
            "adambrett/Upload": "1.3.0-p1"
        }
    }

Install via composer:

    php composer.phar install

## Author

[Josh Lockhart](https://github.com/codeguy)
[Adam Brett](https://github.com/adambrett)

## License

MIT Public License
