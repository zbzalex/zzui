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

  public function doFilter(HttpRequest $request, HttpResponse $response, FilterChain $filterChain)
  {
    $this->app->setRequest($request);
    $this->app->setResponse($response);

    try {

      $requestTarget = $this->app->getUrlCoder()->decode($request);
      if ($requestTarget === null) {
        $requestTarget = new ListenerRequestTarget($this->app->getHomePage(), null, null);
      }

      $this->app->setRequestTarget($requestTarget);

      $pageFactory = $this->app->getPageFactory();

      if ($requestTarget instanceof ListenerRequestTarget) {

        if (
          ($authorizationStrategy = $this->app->getAuthorizationStrategy()) !== null
          && !$authorizationStrategy->isInstantiationAuthorized($requestTarget->getPageClass(), $this->app)
        ) {
          throw new UnauthorizedException();
        }

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
      //$this->app->setResponsePage($pageFactory->newPage(AccessDeniedPage::class));
      // ignore
    } catch (PageNotFoundException $e) {
      $this->app->setResponsePage($pageFactory->newPage(NotFoundPage::class));
    } catch (\Exception $e) {
      // TODO make log
      $this->app->setResponsePage($pageFactory->newPage(ErrorPage::class));
    }

    $this->app->getRequestTarget()->respond($this->app);

    $filterChain->doFilter($request, $response);
  }
}
