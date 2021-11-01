<?php

declare(strict_types=1);

namespace App\Controller\Media;

use App\Entity\Media;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CreateMediaAction
{
    public function __invoke(Request $request): Media
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $media = new Media();
        $media->setFile($uploadedFile);
        $media->setTitle($request->request->get('title'));
        $media->setLegend($request->request->get('legend'));
        $media->setTarget($request->request->get('target'));

        return $media;
    }
}
