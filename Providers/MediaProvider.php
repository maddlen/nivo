<?php

namespace Maddlen\Nivo\Providers;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\View\Asset\Repository;

readonly class MediaProvider implements ContainerProviderInterface
{
    public function __construct(
        public Repository $assetRepository,
        public Filesystem $filesystem,
    )
    {
    }

    public function viewFilepath(string $relativePath): string
    {
        $file = $this->assetRepository->createAsset($relativePath);
        return $file->getSourceFile();
    }

    public function mediaFilepath(string $relativePath): string
    {
        return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath() . $relativePath;
    }
}
