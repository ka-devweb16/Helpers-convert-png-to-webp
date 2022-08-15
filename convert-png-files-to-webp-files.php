<?php

/**
 * @author Kev
 * @param string $directory The directory where the files are localized (supports .png or .PNG extensions).
 * @return array PNG files array or empty array.
 */
function getPNGFilesFromDirectory(string $directory): array
{
    $pattern = "{$directory}*.[pP][nN][gG]";

    $files = glob(
        pattern: $pattern,
        flags: GLOB_BRACE
    );
    
    if ($files === false) {
        throw new LogicException(
            message: 'glob() failed'
        );
    }

    return $files;
}

/**
 * @author Kev
 * @param array $files An array with PNG files to convert to WebP files.
 * @return array An array with WebP filenames.
 */
function convertPNGToWebP(array $files): array
{
    $fileNames = [];

    foreach($files as $file) {
        [
            'dirname'  => $dirName,
            'filename' => $inputFilenameWithoutExtension
         ] = pathinfo(
            path: $file
        );

        $inputImage = imagecreatefrompng(
            filename: $file
        );
    
        if ($inputImage === false) {
            throw new LogicException(
                message: 'imagecreatefrompng() failed'
            );
        }
    
        $booleanResult = imagesavealpha(
            image: $inputImage, 
            enable: true
        );
    
        if ($booleanResult === false) {
            throw new LogicException(
                message: 'imagesavealpha() failed'
            );
        }

        $booleanResult = imagepalettetotruecolor(
            image: $inputImage
        );

        if ($booleanResult === false) {
            throw new LogicException(
                message: 'imagepalettetotruecolor() failed'
            );
        }

        $outputFilename = "{$dirName}/{$inputFilenameWithoutExtension}.webp";

        $booleanResult = imagewebp(
            $inputImage,
            $outputFilename,
        );
    
        if ($booleanResult === false) {
            throw new LogicException(
                message: 'imageweb() failed'
            );
        }

        $fileNames[] = $outputFilename;
    }

    return $fileNames;
}
