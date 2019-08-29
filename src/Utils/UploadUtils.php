<?php


namespace App\Utils;


use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadUtils
{
    public function upload($file,$folder)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename.'.'.$file->getClientOriginalExtension();

        try {
            $file->move(
                $folder,
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        return $newFilename;
    }

    public function uploadUserPicture($file,$folder,$filename)
    {
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $filename);
        $newFilename = $safeFilename.'.'.$file->getClientOriginalExtension();

        try {
            $file->move(
                $folder,
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        return $newFilename;
    }

}