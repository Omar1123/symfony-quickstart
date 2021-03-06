<?php

namespace BaseBundle\Twig\Extension;

use BaseBundle\Base\BaseTwigExtension;

class LightExtension extends BaseTwigExtension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('http_build_query', 'http_build_query', ['is_safe' => ['html', 'html_attr']]),
            new \Twig_SimpleFunction('array_to_query_fields', [$this, 'arrayToQueryFields'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('absolute_url', [$this, 'absoluteUrl']),
            new \Twig_SimpleFunction('current_route', [$this, 'currentRoute']),
            new \Twig_SimpleFunction('current_locale', [$this, 'currentLocale']),
            new \Twig_SimpleFunction('current_uri', [$this, 'currentUri']),
        ];
    }

    public function arrayToQueryFields($key, $value, $keyPrefix = null)
    {
        $currentKey = $keyPrefix ? $keyPrefix.'['.$key.']' : $key;

        if (is_string($value)) {
            return '<input type="hidden" name="'.htmlentities($currentKey).'" value="'.htmlentities($value).'"/>';
        }

        $inputs = '';
        foreach ($value as $childKey => $childValue) {
            $inputs .= $this->arrayToQueryInputs($childKey, $childValue, $currentKey);
        }

        return $inputs;
    }

    public function absoluteUrl($asset)
    {
        $request = $this->get('request_stack')->getMasterRequest();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        return $baseurl . $asset;
    }

    public function currentRoute()
    {
        return $this->get('base.routing.helper')->getCurrentRoute();
    }

    public function currentLocale()
    {
        return $this->get('request_stack')->getMasterRequest()->getLocale();
    }

    public function currentUri()
    {
        return $this->get('request_stack')->getMasterRequest()->getUri();
    }

    public function getName()
    {
        return 'light';
    }
}
