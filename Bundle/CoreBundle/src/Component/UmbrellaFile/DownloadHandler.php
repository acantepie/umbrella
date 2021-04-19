<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile;

use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Umbrella\CoreBundle\Component\UmbrellaFile\Storage\FileStorage;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

class DownloadHandler
{
    private FileStorage $fileStorage;

    /**
     * DownloadHandler constructor.
     */
    public function __construct(FileStorage $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }

    public function loadFromRequest(Request $request): ?UmbrellaFile
    {
        return $this->fileStorage->loadFromUriAttributes($request->attributes->all());
    }

    public function downloadOrNotFound(UmbrellaFile $file, bool $forceDownload = true, string $notFoundMessage = 'Resource not found.'): Response
    {
        try {
            return $this->download($file, $forceDownload);
        } catch (FilesystemException $e) {
            return new Response($notFoundMessage, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function download(UmbrellaFile $file, bool $forceDownload = true): StreamedResponse
    {
        $stream = $this->fileStorage->readStream($file);
        $mimeType = $this->fileStorage->getMimeType($file);

        return $this->createResponse($stream, $file->name, $mimeType, $forceDownload);
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    private function createResponse($stream, string $filename, ?string $mimeType = 'application/octet-stream', bool $forceDownload = true): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($stream) {
            \stream_copy_to_stream($stream, fopen('php://output', 'wb'));
        });

        $disposition = $response->headers->makeDisposition(
            $forceDownload ? ResponseHeaderBag::DISPOSITION_ATTACHMENT : ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            \filter_var($filename, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)
        );

        $response->headers->set('Content-Type', $mimeType ?: 'application/octet-stream');

        return $response;
    }
}
