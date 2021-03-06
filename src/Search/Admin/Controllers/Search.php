<?php 
namespace Search\Admin\Controllers;

class Search extends \Admin\Controllers\BaseAuth 
{
   
    public function index()
    {
    	 
    	$filter =  $this->input->get('filter','','array');
    	$settings = \Search\Models\Settings::fetch();
    	if(empty($filter)){
    		$this->allSearch();
    	} else {
    		$this->filteredSearch();
    	}
    
    }
    
    protected function allSearch() {
    	$q = $this->input->get( 'q', null, 'default' );
    	$sources = \Search\Factory::sources();

    	$count = 0;
    	$counts = array();
    	$results = array();
    	foreach($sources as $key => $source) 
    	{
    		$paginated = \Search\Models\Source::paginate( $source, $q );
    		if (!empty($paginated->items)) 
    		{
    			$results[$source['title']] = array_slice($paginated->items,0,2);
    		}
    		$counts[$source['title']] = \Search\Models\Source::count( $source, $q );
    		$count = $count + $counts[$source['title']];
    	}
    	 
    	$this->app->set('current_source', 'all' );
    	$this->app->set('results', $results );
    	$this->app->set('counts', $counts );
    	$this->app->set('count', $count );
    	$this->app->set('q', $q );
    	
    	$this->app->set('meta.title', trim( 'Search All' ) );
    	
    	echo $this->theme->render('Search/Admin/Views::search/all.php');
    	 
    }
    
    protected function filteredSearch() {
    	$q = $this->input->get( 'q', null, 'default' );
    	
    	try {
    		$current_source = \Search\Models\Source::current();
    		$paginated = \Search\Models\Source::paginate( $current_source, $q );
    		$count = 0;
    		foreach(\Search\Factory::sources() as $key => $source)
    		{
    		    $count = $count + \Search\Models\Source::count( $source, $q );
    		}
    		
    	}
    	catch (\Exception $e) {
    		$this->app->error(404, 'Search Type Not Found');
    		return;
    	}
    	 
    	$this->app->set('current_source', $current_source );
    	$this->app->set('paginated', $paginated );
    	$this->app->set('count', $count );
    	$this->app->set('q', $q );
    	 
    	$this->app->set('meta.title', trim( 'Search ' . $current_source['title'] ) );
    
        echo $this->theme->render('Search/Admin/Views::search/index.php');
    	    
    }
    
    
}