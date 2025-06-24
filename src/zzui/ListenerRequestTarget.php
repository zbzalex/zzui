<?php

namespace zzui;

use zzui\markup\html\Page;

class ListenerRequestTarget extends RequestTargetImpl implements RequestTarget, EventTarget
{
    /**
     * @var string
     */
    protected $pageClass;

    /**
     * @var string
     */
    protected $componentId;

    /**
     * @var string
     */
    protected $listener;

    /**
     * @var array
     */
    protected $params;

    public function __construct(
        $pageClass,
        $componentId,
        $listener,
        array $params = []
    ) {
        $this->pageClass = $pageClass;
        $this->componentId = $componentId;
        $this->listener = $listener;
        $this->params = $params;
    }

    public function getPageClass()
    {
        return $this->pageClass;
    }

    public function getComponentId()
    {
        return $this->componentId;
    }

    public function getListener()
    {
        return $this->listener;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @see \zzui\EventTarget::processEvents()
     */
    public function processEvents(Context $app, Page $page)
    {
        if ($this->componentId !== null) {
            $component = $page->findChildById($this->componentId);
            $listeners = [
                '\\zzui\\markup\\html\\FormListener' => 'onSubmit',
                '\\zzui\\markup\\html\\LinkListener' => 'onClick',
            ];
            foreach ($listeners as $listener => $method) {
                if (
                    $this->listener === $listener
                    && is_subclass_of($component, $listener)
                ) {
                    $args = [];

                    if (
                        $app->getRequest()->getMethod() === 'POST' &&
                        $listener === '\\zzui\\markup\\html\\FormListener'
                    ) {
                        $args[] = $app->getRequest()->getBody();
                    }

                    $reflectionMethod = new \ReflectionMethod($component, $method);
                    $reflectionMethod->invokeArgs($component, $args);
                    break;
                }
            }
        }
    }

    /**
     * @see \zzui\RequestTarget::respond()
     */
    public function respond(Context $app)
    {
        $response = $app->getResponse();
        $responsePage = $app->getResponsePage();
        if ($responsePage !== null) {

            if ($app->isRedirect()) {
                $response->setHeader(
                    'Location',
                    $app->getUrlCoder()->encode(
                        new ListenerRequestTarget(
                            is_array($responsePage) ? $responsePage[0] : get_class($responsePage),
                            null,
                            null,
                            is_array($responsePage) ? $responsePage[1] : $responsePage->getParams()
                        )
                    )
                );
            } else {
                $responsePage->render($app);
            }
        }
    }
}
