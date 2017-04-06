<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Installer;

use Zhiyi\Component\Installer\PlusInstallPlugin\ComponentInfoInterface;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\{
    asset
};

class Info implements ComponentInfoInterface
{
    /**
     * Get the component name.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function getName(): string
    {
        return '音乐模块';
    }

    /**
     * Get the component logo.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function getLogo(): string
    {
        return asset('logo.png');
    }

    /**
     * Get the component Icon.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function getIcon(): string
    {
        return asset('icon.png');
    }

    /**
     * Get the component admin entry.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function getAdminEntry()
    {
        return route('music:admin');
    }
}
