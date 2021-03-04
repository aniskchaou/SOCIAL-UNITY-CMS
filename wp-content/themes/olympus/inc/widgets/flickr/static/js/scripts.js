/*
 * Copyright (C) 2009 Joel Sutherland
 * Licenced under the MIT license
 * http://www.newmediacampaigns.com/page/jquery-flickr-plugin
 *
 * Available tags for templates:
 * title, link, date_taken, description, published, author, author_id, tags, image*
 */
!function(e){e.fn.jflickrfeed=function(i,a){i=e.extend(!0,{flickrbase:"//api.flickr.com/services/feeds/",feedapi:"photos_public.gne",limit:20,qstrings:{lang:"en-us",format:"json",jsoncallback:"?"},cleanDescription:!0,useTemplate:!0,itemTemplate:"",itemCallback:function(){}},i);var m=i.flickrbase+i.feedapi+"?",t=!0;for(var c in i.qstrings)t||(m+="&"),m+=c+"="+i.qstrings[c],t=!1;return e(this).each(function(){var t=e(this),c=this;e.getJSON(m,function(m){e.each(m.items,function(e,a){if(e<i.limit){if(i.cleanDescription){var m=/<p>(.*?)<\/p>/g,n=a.description;m.test(n)&&(a.description=n.match(m)[2],void 0!=a.description&&(a.description=a.description.replace("<p>","").replace("</p>","")))}if(a.media.m=a.media.m.replace("http://","//"),a.image_s=a.media.m.replace("_m","_s"),a.image_q=a.media.m.replace("_m","_q"),a.image_t=a.media.m.replace("_m","_t"),a.image_m=a.media.m.replace("_m","_m"),a.image=a.media.m.replace("_m",""),a.image_b=a.media.m.replace("_m","_b"),delete a.media,i.useTemplate){var r=i.itemTemplate;for(var l in a){var p=new RegExp("{{"+l+"}}","g");r=r.replace(p,a[l])}t.append(r)}i.itemCallback.call(c,a)}}),e.isFunction(a)&&a.call(c,m)})})}}(jQuery);