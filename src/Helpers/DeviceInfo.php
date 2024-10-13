<?php

namespace Shika\Helpers;

use DeviceDetector\DeviceDetector;
use Psr\Http\Message\RequestInterface;

/**
 * Helper for getting information about the agent/device
 */
class DeviceInfo
{
    private DeviceDetector $detector;

    /**
     * Constructs a new device info from a request
     */
    public function __construct(RequestInterface $request)
    {
        $userAgent = $request->getHeaderLine("User-Agent");

        $detector = new DeviceDetector($userAgent);
        $detector->parse();

        $this->detector = $detector;
    }

    /**
     * Gets the device operating system
     */
    public function getOperatingSystem(): string
    {
        return $this->detector->getOs("name");
    }

    /**
     * Gets the browser name
     */
    public function getBrowser(): string
    {
        $browser = $this->detector->getClient("name");

        switch ($browser)
        {
            case "Chrome Mobile":
            case "Chrome Mobile iOS":
                return "Chrome";

            case "Firefox Mobile":
            case "Firefox Mobile iOS":
                return "Firefox";

            case "Mobile Safari":
                return "Safari";

            case "Opera Mobile":
                return "Opera";

            case "Opera Mini iOS":
                return "Opera Mini";

            case "Huawei Browser Mobile":
                return "Huawei Browser";

            default:
                return $browser;
        }
    }

    /**
     * Gets the device type (phone, tablet, desktop)
     */
    public function getDeviceType(): string
    {
        return ($this->detector->isTablet() ? "tablet" : ($this->detector->isMobile() ? "phone" : "desktop"));
    }

    /**
     * Gets whether the agent is a browser
     */
    public function isBrowser():  bool
    {
        return $this->detector->isBrowser();
    }
}