/* jce - 2.9.29 | 2022-08-09 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2022 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function(){var DOM=tinymce.DOM,Delay=tinymce.util.Delay;tinymce.PluginManager.add("wordcount",function(ed,url){function processText(tx){var tc=0;if(!tx)return tc;tx=tx.replace(/\.\.\./g," "),tx=tx.replace(/<.[^<>]*?>/g," ").replace(/&nbsp;|&#160;/gi," "),tx=tx.replace(/(\w+)(&#?[a-z0-9]+;)+(\w+)/i,"$1$3").replace(/&.+?;/g," "),tx=tx.replace(cleanre,"");var wordArray=tx.match(countre);return wordArray&&(tc=wordArray.length),tc}function getCount(){var tx=ed.getContent({format:"raw",no_events:!0});return processText(tx)}function countChars(tc){if(!ed.destroyed){if(tc)return DOM.setAttrib(target_id,"title",ed.getLang("wordcount.selection","Selected Words:")),DOM.setHTML(target_id,tc.toString());var limit=parseInt(ed.getParam("wordcount_limit",0),10),showAlert=ed.getParam("wordcount_alert",0);tc=getCount(),limit&&(tc=limit-tc,tc<0?(DOM.addClass(target_id,"mceWordCountLimit"),showAlert&&ed.windowManager.alert(ed.getLang("wordcount.limit_alert","You have reached the word limit set for this content."))):DOM.removeClass(target_id,"mceWordCountLimit")),DOM.setAttrib(target_id,"title",ed.getLang("wordcount.words","Words:")),DOM.setHTML(target_id,tc.toString()),ed.onWordCount.dispatch(ed,tc)}}var self=this,countre=ed.getParam("wordcount_countregex",/[\w\u2019\x27\-\u00C0-\u1FFF]+/g),cleanre=ed.getParam("wordcount_cleanregex",/[0-9.(),;:!?%#$?\x27\x22_+=\\\/\-]*/g),update_rate=ed.getParam("wordcount_update_rate",200),target_id=ed.id+"_word_count";ed.onWordCount=new tinymce.util.Dispatcher(self),ed.onPostRender.add(function(ed,cm){if(target_id=ed.getParam("wordcount_target_id",target_id),!DOM.get(target_id)){var row=DOM.get(ed.id+"_path_row");if(row){var label=ed.getLang("wordcount.words","Words:");DOM.add(row.parentNode,"div",{class:"mceWordCount"},'<span title="'+label+'" id="'+target_id+'" class="mceText">0</span>')}}});var countAll=Delay.debounce(function(){countChars()},update_rate),countSelection=Delay.debounce(function(){var rng=ed.selection.getRng(),sel=ed.selection.getSel();if(rng.collapsed)countChars();else{var text=rng.text||(sel.toString?sel.toString():""),count=processText(text);countChars(count)}},update_rate);ed.onSelectionChange.add(countSelection),ed.onInit.add(countAll)})}();