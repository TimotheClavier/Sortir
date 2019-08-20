<?php


namespace App\Utils;


use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadUtils
{
    public function upload($file,$folder)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename.'.'.$file->getClientOriginalExtension();

        // Move the file to the directory where brochures are stored
        try {
            $file->move(
                $folder,
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        // updates the 'brochureFilename' property to store the PDF file name
        // instead of its contents
        return $newFilename;
    }

    public function uploadUserPicture($file,$folder,$filename)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $filename);
        $newFilename = $safeFilename.'.'.$file->getClientOriginalExtension();

        // Move the file to the directory where brochures are stored
        try {
            $file->move(
                $folder,
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        // updates the 'brochureFilename' property to store the PDF file name
        // instead of its contents
        return $newFilename;
    }

}