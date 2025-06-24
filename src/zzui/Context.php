<?php

namespace zzui;

use zzui\content\ResourceManager;
use zzui\http\Request;
use zzui\http\Response;
use zzui\markup\html\Page;

/**
 * Application context class.
 * 
 * @author zbzalex
 */
class Context
{
    /**
     * @var \zzui\content\ResourceManager
     */
    protected $resourceManager;

    /**
     * @var \zzui\http\Request
     */
    protected $request;

    /**
     * @var \zzui\RequestTarget
     */
    protected $requestTarget;

    /**
     * @var \zzui\http\Response
     */
    protected $response;

    /**
     * @var array|Page
     */
    protected $responsePage;

    /**
     * @var boolean
     */
    protected $redirect;

    /**
     * @var \zzui\AuthorizationStrategy
     */
    protected $authorizationStrategy;

    /**
     * Constructor.
     */
    public function __construct(ResourceManager $resourceManager)
    {
        $this->resourceManager = $resourceManager;
        $this->response = new Response();
    }

    public function getResourceManager()
    {
        return $this->resourceManager;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequestTarget($target)
    {
        $this->requestTarget = $target;
    }

    public function getRequestTarget()
    {
        return $this->requestTarget;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return \zzui\http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    public function isRedirect()
    {
        return $this->redirect;
    }

    public function setResponsePage($responsePage)
    {
        $this->responsePage = $responsePage;
    }

    public function getResponsePage()
    {
        return $this->responsePage;
    }

    /**
     * Page factory class.
     * 
     * @see \zzui\PageFactory
     */
    public function getPageFactory()
    {
        return new DefaultPageFactory($this);
    }

    /**
     * @see \zzui\UrlCoder
     */
    public function getUrlCoder()
    {
        return new DefaultUrlCoder();
    }

    public function getAuthorizationStrategy()
    {
        return $this->authorizationStrategy;
    }

    public function setAuthorizationStrategy(AuthorizationStrategy $strategy)
    {
        $this->authorizationStrategy = $strategy;
    }
}
