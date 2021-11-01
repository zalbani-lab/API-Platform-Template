<?php

declare(strict_types=1);

namespace App\Controller\Media;

use App\Entity\Media;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class UpdateMediaAction
{
    public function __invoke(Media $data, Request $request): Media
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }
        $data->setFile($uploadedFile);
        if ($request->request->get('title')) {
            $data->setTitle($request->request->get('title'));
        }
        if ($request->request->get('legend')) {
            $data->setLegend($request->request->get('legend'));
        }
        if ($request->request->get('target')) {
            $data->setTarget($request->request->get('target'));
        }

        return $data;
    }
}
