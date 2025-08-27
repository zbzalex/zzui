<?php

namespace zzui;

use zzui\http\Filter;
use zzui\http\FilterChain;
use zzui\http\HttpRequest;
use zzui\http\HttpResponse;
use zzui\pages\NotFoundPage;
use zzui\pages\ErrorPage;

class RequestProcessorFilter implements Filter
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function doFilter(
        HttpRequest $request,
        HttpResponse $response,
        FilterChain $filterChain
    ) {
        $this->app->setRequest($request);
        $this->app->setResponse($response);

        $requestTarget = null;

        try {
            $requestTarget = $this->app->getUrlCoder()->decode($request);
            if ($requestTarget == null) {
                $requestTarget = new ListenerRequestTarget($this->app->getHomePage(), null, null);
            }

            $this->app->setRequestTarget($requestTarget);

            $pageFactory = $this->app->getPageFactory();

            if ($requestTarget instanceof ListenerRequestTarget) {
                $page = $pageFactory->newPage($requestTarget->getPageClass(), $requestTarget->getParams());

                if ($page === null) {
                    throw new PageNotFoundException();
                }

                $this->app->setResponsePage($page);

                if ($requestTarget instanceof EventTarget) {
                    $requestTarget->processEvents($this->app, $page);
                }
            }
        } catch (UnauthorizedException $e) {
        } catch (PageNotFoundException $e) {
            $this->app->setResponsePage($pageFactory->newPage(NotFoundPage::class));
        } catch (\Exception $e) {
            $this->app->setResponsePage($pageFactory->newPage(ErrorPage::class));
        }

        try {
            $this->app->getRequestTarget()->respond($this->app);
            $filterChain->doFilter($request, $response);
        } catch (\Exception $e) {

            var_dump($e->getMessage());
        }
    }
}
