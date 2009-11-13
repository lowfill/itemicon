Itemicon plugin
===============

Add a couple of views that let you assign icons for your blog posts or another objects

Requirements
------------
  blogextended 1.3.1 for example case use


Install
-------

After check that you have blogextended 1.3.1 installed drop this plugin on your mod directory and then go to the admin panel and activate it.


How to enable itemicon form my object?
--------------------------------------
If you wants to add the itemicon support for your plugin you must:
  
  1) Call/extend your item edit form with itemicon/view.
     For example in blogextended:
     
       if(is_plugin_enabled("itemicon")){
         extend_view("blog/fields_after","itemicon/add");
       }
       
  2) Add 'entity_id' to your profile/icon call 
     For example in blogextended:
     
       echo elgg_view("profile/icon",array('entity' => $owner, 'size' => 'small','entity_id'=>$vars['entity']->guid));
       
  3) Register your object subtype in the supported types in $CONFIG->itemicon
  	 For example in blogextended:
  	 
  	   if(!isset($CONFIG->itemicon)){
  	     $CONFIG->itemicon[]=array();
  	   }
  	   $CONFIG->itemicon[] = "blog";
     