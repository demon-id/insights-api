<?php
namespace insights\api\crawler;

use GuzzleHttp\Client AS HTTPClient;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class CrawlerApiClient extends Component {

	public $apiUrl;

	public $apiKey;

	public $testUserIds;

	protected $HTTPClient;

	protected $methodParams = [
		'get-sites' => [
			'url' => 'sites/index',
			'type' => 'get'
		],
		'check-site-available' => [
			'url' => 'sites/check-site-available',
			'type' => 'get'
		],
		'add-site' => [
			'url' => 'sites/add-site',
			'type' => 'post'
		],
		'delete-site' => [
			'url' => 'sites/delete-site',
			'type' => 'put'
		],
		'add-keywords' => [
			'url' => 'site-keywords/add-keywords',
			'type' => 'post'
		],
		'get-site-keywords' => [
			'url' => 'site-keywords/get-site-keywords',
			'type' => 'get'
		],
		'delete-site-keyword' => [
			'url' => 'site-keywords/delete-site-keyword',
			'type' => 'put'
		],
		'start-grab-site-robots' => [
			'url' => 'sites/start-grab-robots',
			'type' => 'post'
		],
		'stop-grab-site-robots' => [
			'url' => 'sites/stop-grab-robots',
			'type' => 'post'
		],
		'start-grab-sitemap' => [
			'url' => 'site-sitemaps/start-grab-sitemap',
			'type' => 'post'
		],
		'stop-grab-sitemap' => [
			'url' => 'site-sitemaps/stop-grab-sitemap',
			'type' => 'post'
		],
		'start-parse-sitemap-links' => [
			'url' => 'site-sitemaps/start-parse-sitemap-links',
			'type' => 'post'
		],
		'stop-parse-sitemap-links' => [
			'url' => 'site-sitemaps/stop-parse-sitemap-links',
			'type' => 'post'
		],
		'start-grab-external-link' => [
			'url' => 'external-links/start-grab-link',
			'type' => 'post'
		],
		'stop-grab-external-link' => [
			'url' => 'external-links/stop-grab-link',
			'type' => 'post'
		],
		'get-site-page' => [
			'url' => 'site-pages/get-page',
			'type' => 'get'
		],
		'start-grab-site-page' => [
			'url' => 'site-pages/start-grab-page',
			'type' => 'post'
		],
		'stop-grab-site-page' => [
			'url' => 'site-pages/stop-grab-page',
			'type' => 'post'
		],
		'start-parse-site-page-links' => [
			'url' => 'site-pages/start-parse-page-links',
			'type' => 'post'
		],
		'stop-parse-site-page-links' => [
			'url' => 'site-pages/stop-parse-page-links',
			'type' => 'post'
		],
		'start-parse-site-page-content' => [
			'url' => 'site-pages/start-parse-page-content',
			'type' => 'post'
		],
		'stop-parse-site-page-content' => [
			'url' => 'site-pages/stop-parse-page-content',
			'type' => 'post'
		],
		'start-parse-site-page-keyword' => [
			'url' => 'site-page-keywords/start-parse-page-keyword',
			'type' => 'post'
		],
		'stop-parse-site-page-keyword' => [
			'url' => 'site-page-keywords/stop-parse-page-keyword',
			'type' => 'post'
		],
		'start-grab-serp' => [
			'url' => 'keywords-serp/start-grab-serp',
			'type' => 'post'
		],
		'stop-grab-serp' => [
			'url' => 'keywords-serp/stop-grab-serp',
			'type' => 'post'
		],
	];

	public function __construct() {
		$this->HTTPClient = new HTTPClient;
	}

	protected function sendRequest($name, $params=[], $headers=[])
	{
		$headers = ArrayHelper::merge($headers, [
			'api-key' => $this->apiKey
		]);

		$request_params = $this->getRequestParams($name);
		$request_type = $request_params['type'];
		$request_url = $this->apiUrl.$request_params['url'];
		$response = $this->HTTPClient->$request_type($request_url, [
			'headers' => $headers,
			'body' => $params
		]);

		$answer = $response->json();

		return $answer;
	}

	protected function getRequestParams($name)
	{
		if (empty($this->methodParams[$name])) {
			throw new \BadMethodCallException('Api method is undefined.');
		}

		return $this->methodParams[$name];
	}

	public function getSites()
	{
		return $this->sendRequest('get-sites');
	}

	/**
	 * Check site availability
	 * @param $url
	 * @return bool
	 */
	public function checkSiteAvailable($url)
	{
		return $this->sendRequest('check-site-available', ['url'=>$url]);
	}

	public function addSite($site_id, $url)
	{
		return $this->sendRequest('add-site', ['site_id'=>$site_id, 'url'=>$url]);
	}

	public function deleteSite($site_id)
	{
		return $this->sendRequest('delete-site', ['site_id'=>$site_id]);
	}

	public function addKeywords($site_id, $keywords=[])
	{
		return $this->sendRequest('add-keywords', ['site_id'=>$site_id, 'keywords'=>$keywords]);
	}

	public function getSiteKeywords($site_id)
	{
		return $this->sendRequest('get-site-keywords', ['site_id'=>$site_id]);
	}

	public function deleteSiteKeyword($id, $site_id)
	{
		return $this->sendRequest('delete-site-keyword', ['id'=>$id, 'site_id'=>$site_id]);
	}

	public function startGrabeSiteRobots($site_id)
	{
		return $this->sendRequest('start-grab-site-robots', ['id'=>$site_id]);
	}

	public function stopGrabeSiteRobots($site_id, $data)
	{
		return $this->sendRequest('stop-grab-site-robots', [
			'id' => $site_id,
			'data' => $data
		]);
	}

	public function getSitemap($sitemap_id)
	{
		return $this->sendRequest('get-site-page', ['id'=>$sitemap_id]);
	}

	public function startGrabeSitemap($sitemap_id)
	{
		return $this->sendRequest('start-grab-sitemap', ['id'=>$sitemap_id]);
	}

	public function stopGrabeSitemap($sitemap_id, $data)
	{
		return $this->sendRequest('stop-grab-sitemap', [
			'id' => $sitemap_id,
			'data' => $data
		]);
	}

	public function startGrabExternalLink($link_id)
	{
		return $this->sendRequest('start-grab-external-link', ['id'=>$link_id]);
	}

	public function stopGrabExternalLink($link_id, $data)
	{
		return $this->sendRequest('stop-grab-external-link', [
			'id' => $link_id,
			'data' => $data
		]);
	}

	public function startParseSitemapLinks($sitemap_id)
	{
		return $this->sendRequest('start-parse-sitemap-links', ['id'=>$sitemap_id]);
	}
	public function stopParseSitemapLinks($sitemap_id, $links)
	{
		return $this->sendRequest('stop-parse-sitemap-links', [
			'id' => $sitemap_id,
			'links' => $links
		]);
	}

	public function getSitePage($page_id)
	{
		return $this->sendRequest('get-site-page', ['id'=>$page_id]);
	}

	public function startGrabeSitePage($page_id)
	{
		return $this->sendRequest('start-grab-site-page', ['id'=>$page_id]);
	}

	public function stopGrabeSitePage($page_id, $data)
	{
		return $this->sendRequest('stop-grab-site-page', [
			'id' => $page_id,
			'data' => $data
		]);
	}

	public function startParseSitePageLinks($page_id)
	{
		return $this->sendRequest('start-parse-site-page-links', ['id'=>$page_id]);
	}

	public function stopParseSitePageLinks($page_id, $links)
	{
		return $this->sendRequest('stop-parse-site-page-links', [
			'id' => $page_id,
			'links' => $links
		]);
	}

	public function startParseSitePageContent($page_id)
	{
		return $this->sendRequest('start-parse-site-page-content', ['id'=>$page_id]);
	}

	public function stopParseSitePageContent($page_id, $data)
	{
		return $this->sendRequest('stop-parse-site-page-content', [
			'id' => $page_id,
			'data' => $data
		]);
	}

	public function startParseSitePageKeyword($keyword_id)
	{
		return $this->sendRequest('start-parse-site-page-keyword', ['id'=>$keyword_id]);
	}

	public function stopParseSitePageKeyword($keyword_id, $data)
	{
		return $this->sendRequest('stop-parse-site-page-keyword', [
			'id' => $keyword_id,
			'data' => $data
		]);
	}

	public function startGrabSerp($keyword_id)
	{
		return $this->sendRequest('start-grab-serp', ['id'=>$keyword_id]);
	}

	public function stopGrabSerp($keyword_id, $data)
	{
		return $this->sendRequest('stop-grab-serp', [
			'id' => $keyword_id,
			'data' => $data
		]);
	}
}