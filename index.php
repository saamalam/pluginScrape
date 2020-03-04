
<?php
class Scraper {
  protected $categories = array();
  protected $domain;
  protected $filename = "csv/data.csv";
  
  protected $file_csv;

  

  // Set actions to run when the class is instantiated
  function __construct($url, $data){

    error_reporting(-1);
    ini_set('display_errors', true);
    // Set the maximum execution time of the script to unlimited so that it can grab all the categories if there are a lot of them to scrape
    set_time_limit(0);
    
    $this->file_csv = fopen($this->filename,"w");

    // Set the root domain of the URL to concatinate with URLs later
    $this->domain = explode("/", $url);
    $this->domain = 'https://' . $this->domain[2];

   
    $login = curl_init();
    curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($login, CURLOPT_TIMEOUT, 60000);
    curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($login, CURLOPT_URL, $url);
    curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($login, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($login, CURLOPT_POST, TRUE);
    curl_setopt($login, CURLOPT_POSTFIELDS, $data);
    curl_exec($login);
	
    //curl_close($login);
    
   
          $subcats = array(
            "https://plugintheme.net/product-category/wordpress-themes/blog/",
			"https://plugintheme.net/product-category/wordpress-themes/business/"
                );
      foreach($subcats as $cat){
      //$this->getCategoryUrls($cat);
      //$this->getCategoryProducts($cat);
	  echo $cat.'<br>';
	  $this->getCategoryProducts($cat);
      }

     
      
    
    //$this->getCategoryProducts("https://b2b.drapertools.com/category-products/1165/Air-Impact-Wrenches");
    //$this->getProductInfo("https://b2b.drapertools.com/product/65034/Storm-Force-Air-Ratchet-with-Composite-Body-(1-2inch-Square-Drive)");
  
  }

  // Start Get Article Urls
  private function getCategoryUrls($catLink){
    
    $login_page = curl_init();
    curl_setopt($login_page, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($login_page, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($login_page, CURLOPT_TIMEOUT, 60000);
    curl_setopt($login_page, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($login_page, CURLOPT_URL, $catLink);
    curl_setopt($login_page, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($login_page, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($login_page, CURLOPT_FOLLOWLOCATION, TRUE);
    //curl_setopt($login_page, CURLOPT_POST, TRUE);
    //curl_setopt($login_page, CURLOPT_POSTFIELDS, $data);
    //ob_start();
    $html = curl_exec($login_page);
    //var_dump($html);
    if (!$html) {
      echo "cURL error number: " .curl_errno($login_page) . " on URL: " . $catLink ." " . "cURL error: " . curl_error($login_page) . " ";
      }
    
    //ob_end_clean();
    curl_close($login_page);
    //unset($login_page);   
    $dom = new DOMDocument();
      @$dom->loadHtml($html);
  
      $xpath = new DOMXPath($dom);
  
      // Get a list of categories from the section page
    $categoryList = $xpath->query("//div[contains(@class, 'item') and contains(@class, 'category')]");
    foreach ($categoryList as $item){
      //var_dump($item);
     //echo $item->getElementsByTagName('a')->item(1)->nodeValue . "<br>";
      //echo $this->domain . $item->getElementsByTagName('a')->item(1)->getAttribute('href').'<br>';
     $this->getCategoryProducts($this->domain . $item->getElementsByTagName('a')->item(1)->getAttribute('href'), $item->getElementsByTagName('a')->item(1)->nodeValue);
      
    }
    
    }

    // Start Get Article Urls
  private function getCategoryUrlsSub($SubcatLink){
    
    $sub_cat_link = curl_init();
    curl_setopt($sub_cat_link, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($sub_cat_link, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($sub_cat_link, CURLOPT_TIMEOUT, 60000);
    curl_setopt($sub_cat_link, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($sub_cat_link, CURLOPT_URL, $SubcatLink);
    curl_setopt($sub_cat_link, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($sub_cat_link, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($sub_cat_link, CURLOPT_FOLLOWLOCATION, TRUE);
    //curl_setopt($login_page, CURLOPT_POST, TRUE);
    //curl_setopt($login_page, CURLOPT_POSTFIELDS, $data);
    //ob_start();
    $html = curl_exec($sub_cat_link);
    //var_dump($html);
    if (!$html) {
      echo "cURL error number: " .curl_errno($sub_cat_link) . " on URL: " . $SubcatLink ." " . "cURL error: " . curl_error($sub_cat_link) . " ";
      }
    
    //ob_end_clean();
    curl_close($sub_cat_link);
    //unset($login_page);   
    $dom = new DOMDocument();
      @$dom->loadHtml($html);
  
      $xpath = new DOMXPath($dom);
  
      // Get a list of categories from the section page
    $categoryList = $xpath->query("//div[contains(@class, 'item') and contains(@class, 'category')]");
    foreach ($categoryList as $item){
      //var_dump($item);
     //echo $item->getElementsByTagName('a')->item(1)->nodeValue . "<br>";
      //echo $this->domain . $item->getElementsByTagName('a')->item(1)->getAttribute('href').'<br>';
     $this->getCategoryProducts($this->domain . $item->getElementsByTagName('a')->item(1)->getAttribute('href'));
      
    }
    
    }
    
    
    
      private function getCategoryProducts($url_cat){
        //echo $url_cat. "<br>";
       
        
    $c_url = curl_init();
    curl_setopt($c_url, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($c_url, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($c_url, CURLOPT_TIMEOUT, 60000);
    curl_setopt($c_url, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($c_url, CURLOPT_URL, $url_cat);
    curl_setopt($c_url, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($c_url, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c_url, CURLOPT_FOLLOWLOCATION, TRUE);
    // Add curl_setopt here to grab a proxy from your proxy list so that you don't get 403 errors from your IP being banned by the site

    // Grab the data.
    $html_cat = curl_exec($c_url);
   
    
  // Check if the HTML didn't load right, if it didn't - report an error
    if (!$html_cat) {
      echo "cURL error number: " .curl_errno($c_url) . " on URL: " . $url_cat ." " . "cURL error: " . curl_error($c_url) . " ";
    }
	
	

    // Close connection.
    curl_close($c_url);
	
	//echo $html_cat;
    // Parse the HTML information and return the results.
    $dom_cat = new DOMDocument();
    @$dom_cat->loadHtml($html_cat);

    $xpath_cat = new DOMXPath($dom_cat);

    // Get a list of categories from the section page
    $productLists = $xpath_cat->query("//div[contains(@class, 'product-small')]");
      //var_dump($productLists);
      //print_r($productLists);
      $count1 = 1;
    foreach($productLists as $product){       
     
      $prod_url = $this->domain . $product->getElementsByTagName('a')->item(0)->getAttribute('href');
      //echo $prod_url."<br>";
     //$this->getProductInfo($prod_url, $cat_name);
    
      
    //$url_product = $this->domain . $product->getElementsByTagName('a')->item(0)->getAttribute('href');
   
   
    echo $count1.". Product URL: " . $prod_url;
       //echo $product->getElementsByTagName('img')->item(0)->getAttribute('src') . "<br>";
       //$full_path = "data/categories/".basename($image->getElementsByTagName('img')->item(0)->getAttribute('src'));
       
       //fputcsv($this->file_csv, array($full_path));
      // $img_path = 'https:'.$image->getElementsByTagName('img')->item(0)->getAttribute('src');
       //copy($img_path, $full_path);
       $count1++;
      
    }
   // echo $count1 . "<br>";
        
        
        
      }
      
private function getProductInfo($prod_url, $cat_name_final){
        
        
    $p_url = curl_init();
    
    curl_setopt($p_url, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($p_url, CURLOPT_COOKIEFILE, "cookie.txt");
    curl_setopt($p_url, CURLOPT_TIMEOUT, 60000);
    curl_setopt($p_url, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($p_url, CURLOPT_URL, $prod_url);
    curl_setopt($p_url, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($p_url, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($p_url, CURLOPT_FOLLOWLOCATION, TRUE);
    // Add curl_setopt here to grab a proxy from your proxy list so that you don't get 403 errors from your IP being banned by the site

    // Grab the data.
    $html_prod = curl_exec($p_url);
    //echo $html_prod;
    
  // Check if the HTML didn't load right, if it didn't - report an error
    if (!$html_prod) {
      echo "cURL error number: " .curl_errno($p_url) . " on URL: " . $html_prod ." " . "cURL error: " . curl_error($p_url) . " ";
    }

    // Close connection.
    curl_close($p_url);

    // Parse the HTML information and return the results.
    $dom_prod = new DOMDocument();
    @$dom_prod->loadHtml($html_prod);

    $xpath_prod = new DOMXPath($dom_prod);

    // Get a list of categories from the section page
    $product_info = $xpath_prod->query("//div[@class='wordpress1170']");
  
    
    foreach($product_info as $info){
      //product name
      $p_name = utf8_decode("Draper ".$info->getElementsByTagName('h1')->item(0)->nodeValue);   
      
      
        $price_str = $xpath_prod->query('//span[@id="showprice"]', $info)->item(0)->nodeValue;
        
        preg_match_all('/\d+\.\d{1,2}/', $price_str, $price_matches);
       // echo "Price: " . $price_matches[0][0]."-----";
        //echo " Nett Price: " . round(trim($price_matches[0][0])*1.40, 2) ."-----";
        

        preg_match_all('!\d+!', $info->getElementsByTagName('h1')->item(1)->nodeValue, $model_matches);
       // echo " Model: " . $model_matches[0][0]."-----";
      
        $status_str =  $xpath_prod->query('//span[@class="leftinstock"]', $info)->item(0)->nodeValue;
        //echo "Status: <b>" . $status_str . "</b><br>";
        if(trim($status_str) == "Discontinued"){
         $status = 0;
        }
          else {
         $status = 1;
          
        }
       
        $category_link = $xpath_prod->query('//ol[@class="breadcrumb"]/li[last()]', $info)->item(0)->nodeValue;
        //echo "Category: ". $category_link;
        //description
        $innerHTML = '';    
        
        $f_desc = $xpath_prod->query('//div[@id="description"]', $info);
        
        if (!$f_desc==0) {
          foreach ($f_desc as $dom1) { 
          $tmp_doc_f = new DOMDocument();
          $tmp_doc_f->appendChild($tmp_doc_f->importNode($dom1,true));
          $innerHTML .= $tmp_doc_f->saveHTML();          
          }
          //echo $innerHTML;          
        }
        

        $p_images = $xpath_prod->query("//img[contains(@class, 'cloudzoom-gallery') and contains(@class, 'cloudzoom-zoom-thumbnail')]", $info);
        //echo count($p_images);
        $additional_images = '';
        $cnt = 0;
        foreach($p_images as $p_image){

          $imageURL = $p_image->getAttribute('src');
         
          $full_path = "catalog/b2b/latest/".basename($imageURL);
          //echo "<br>full path: " . $full_path;
            if($cnt==0){
              $img_path = 'http://b2b.drapertools.com/products/sq/800sq/'.basename($imageURL);
              $main_image = $full_path;

            } else {
              $img_path_check = 'http://b2b.drapertools.com/products/sq/in-use_800sq/'.basename($imageURL); 
             
              if (@getimagesize($img_path_check)) {
                $img_path = $img_path_check;
                } else {
                  $img_path = 'http://b2b.drapertools.com/products/sq/800sq/'.basename($imageURL); 
                }

              $additional_images .= $full_path.';';
            }
          
          //echo "<br>img path: " . $img_path;
          copy($img_path, $full_path);

         $cnt++;
        }
        fputcsv($this->file_csv, array($p_name, round(trim($price_matches[0][0])*1.40, 2), trim($model_matches[0][0]), $status, trim($category_link), $main_image, $additional_images, $innerHTML, $cat_name_final));
        echo "...Done<br>";

    }   
      }
    
    
    
}
$cats = new Scraper('https://plugintheme.net/my-account', 'username=kghost19@gmail.com&password=Admin@7866&woocommerce-login-nonce=1a6db50d39&_wp_http_referer=/my-account/');
?>
