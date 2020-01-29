```php
<?php
    
        require 'vendor/autoload.php';

	use Yandex\Webmaster\WebmasterApi;
	use GuzzleHttp\Client as GuzzleClient;
 	use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

 	$config = [];
 	$guzzle = new GuzzleClient($config);
 	$adapter = new GuzzleAdapter($guzzle);
 	
 	$token = "access token";
 	
 	$wmApi = WebmasterApi::init($adapter, $token);
    
    	try {
    
    		$user_id = $wmApi->getUserId();
    		
    		$hosts = $wmApi->getHosts($user_id);
    
    		$host_id = $wmApi->getHostId($hosts, "lentachel");
    
    		$texts = $wmApi->getOriginalTexts($user_id, $host_id);
    		
    		$content = '<p>article text</p>';
    		$content = html_entity_decode(strip_tags($content));
            
                $result = $wmApi->addOriginalText($user_id, $host_id, $content);
            
            	      
        } catch (Exception $e) {
    	    echo $e->getMessage();
        }

```