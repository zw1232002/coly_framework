<?php  
/**
 * 分页类
 * 
 * @author Coly Zhang  <zw1232002@gmail.com>
 * @version $ID 2014-6-19 $
 */
 class Page {  
  
     private $_configArray = array();
     private $_perPage; //每页显示的条目数  
     private $_nums; //总条目数  
     private $_currentPage; //当前被选中的页  
     private $_subPages; //每次显示的页数  
     private $_pageNums; //总页数  
     private $_pageArray = array (); //用来构造分页的数组  
     private $_page_base_url; //每个分页的链接  
     
     
     /** 
      * 
      * 构造函数
      * 
      * @param array $configArray 分页信息数组
      * @param int     $curPage       当前页
      * 
      */  
     public  function __construct($configArray,$curPage) 
     {
      $this->initialize($configArray,$curPage);
     }
     
     /**
      * 初始化
      *
      * @param array $configArray 分页信息数组
      * @param int     $curPage       当前页
      */
     public function initialize($configArray,$curPage)
     {
        $this->_configArray = $configArray;
        $this->_perPage = intval($this->_configArray['per_page']);
        $this->_nums = intval($this->_configArray['count']);
        if (!$curPage) {
          $this->_currentPage = 1;
        } else {
          $this->_currentPage = intval($curPage);
        }
        $this->_subPages = intval($this->_configArray['num_links']);
        $this->_pageNums = ceil($this->_nums / $this->_perPage);
        $this->_page_base_url =$this->_configArray['base_url'] ? $this->_configArray['base_url'] : $_SERVER['PHP_SELF']."?page=";;
     }
     
   
    /**
     * 用来给建立分页的数组初始化的函数。 
     * 
     * @return array 分页数组
     */  
     private  function _initArray() 
     {  
         for ($i = 0; $i < $this->_subPages; $i++) {  
             $this->_pageArray[$i] = $i;  
         }  
         return $this->_pageArray;  
     }  
   
    /**
     * 构造显示的条目 
     * 
     * @return array
     */  
     private  function _construct_num_Page() 
     {  
         if ($this->_pageNums < $this->_subPages) {  
             $current_array = array ();  
             for ($i = 0; $i < $this->_pageNums; $i++) {  
                 $current_array[$i] = $i +1;  
             }  
         } else {  
             $current_array = $this->_initArray();  
             if ($this->_currentPage <= 3) {  
                 for ($i = 0; $i < count($current_array); $i++) {  
                     $current_array[$i] = $i +1;  
                 }  
             }  
             elseif ($this->_currentPage <= $this->_pageNums && $this->_currentPage > $this->_pageNums - $this->_subPages + 1) {  
                 for ($i = 0; $i < count($current_array); $i++) {  
                     $current_array[$i] = ($this->_pageNums) - ($this->_subPages) + 1 + $i;  
                 }  
             } else {  
                 for ($i = 0; $i < count($current_array); $i++) {  
                     $current_array[$i] = $this->_currentPage - 2 + $i;  
                 }  
             }  
         }  
   
        return $current_array;  
     }  
   
   
    /**
     * 构造分页html
     * 
     * @return string
     */
     public function getPageLink($pageBaseUrl = '')
     { 
        if($pageBaseUrl)
        {
          $this->_page_base_url = strpos($pageBaseUrl, '?')===false ? $pageBaseUrl.'?page=' :  $pageBaseUrl.'&page=';
        }
           
        if(intval($this->_perPage)>=intval($this->_nums)) return '';
        $subPageCss2Str = ''.$this->_configArray['full_tag_open'];  
        
        if ($this->_currentPage > 1) {  
             $firstPageUrl = $this->_page_base_url . "1";  
             $prevPageUrl = $this->_page_base_url . ($this->_currentPage - 1);  
             //首页
             $subPageCss2Str .= $this->_configArray['first_tag_open']."<a href='$firstPageUrl'>".$this->_configArray['first_link'].'</a>'.$this->_configArray['first_tag_close'];
             //上一页
             $subPageCss2Str .= $this->_configArray['prev_tag_open']."<a href='$prevPageUrl'>".$this->_configArray['prev_link'].'</a>'.$this->_configArray['prev_tag_close'];
         }
   
        $a = $this->_construct_num_Page();  
         for ($i = 0; $i < count($a); $i++) {  
             $s = $a[$i];  
             if ($s == $this->_currentPage) {  
              //当前页
              $subPageCss2Str .= $this->_configArray['cur_tag_open'].$s.$this->_configArray['cur_tag_close'];;
             } else {  
                 $url = $this->_page_base_url . $s;  
                 $subPageCss2Str .= $this->_configArray['num_tag_open']."<a href='$url'>".$s.'</a>'.$this->_configArray['num_tag_close'];
             }  
         }  
   
        if ($this->_currentPage < $this->_pageNums) {  
             $lastPageUrl = $this->_page_base_url . $this->_pageNums;  
             $nextPageUrl = $this->_page_base_url . ($this->_currentPage + 1);  
             //末页
             $subPageCss2Str .= $this->_configArray['last_tag_open']."<a href='$lastPageUrl'>".$this->_configArray['last_link'].'</a>'.$this->_configArray['last_tag_close'];
             //下一页
             $subPageCss2Str .= $this->_configArray['next_tag_open']."<a href='$nextPageUrl'>".$this->_configArray['next_link'].'</a>'.$this->_configArray['next_tag_close'];
         }
         
         $subPageCss2Str .= ''.$this->_configArray['full_tag_close'];
         
         return $subPageCss2Str;  
     }  
 }  
 ?>  