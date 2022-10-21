/* jce - 2.9.29 | 2022-08-09 | https://www.joomlacontenteditor.net | Copyright (C) 2006 - 2022 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
!function(){function isSupportedImage(value){return/\.(jpg|jpeg|png|gif|webp|avif)$/.test(value)}function getImageExtension(value){return isSupportedImage(value)?value.substring(value.length,value.lastIndexOf(".")+1):""}function uploadHandler(settings,blobInfo,success,failure,progress){var xhr,formData;xhr=new XMLHttpRequest,xhr.open("POST",settings.url),xhr.upload.onprogress=function(e){progress(e.loaded/e.total*100)},xhr.onerror=function(){failure("Image upload failed due to a XHR Transport error. Code: "+xhr.status)},xhr.onload=function(){var json;return xhr.status<200||xhr.status>=300?void failure("HTTP Error: "+xhr.status):(json=JSON.parse(xhr.responseText),!json||json.error?void failure(json.error.message||"Invalid JSON response!"):json.result&&json.result.files?void success(json.result.files[0]):void failure(json.error.message||"Invalid JSON response!"))},formData=new FormData,formData.append("file",blobInfo.blob(),blobInfo.filename()),each(settings,function(value,name){return"url"==name||"multipart"==name||void formData.append(name,value)}),xhr.send(formData)}function imageToBlobInfo(blobCache,img,resolve,reject){var base64,blobInfo;return 0===img.src.indexOf("blob:")?(blobInfo=blobCache.getByUri(img.src),void(blobInfo?resolve({image:img,blobInfo:blobInfo}):Conversions.uriToBlob(img.src).then(function(blob){Conversions.blobToDataUri(blob).then(function(dataUri){base64=Conversions.parseDataUri(dataUri).data,blobInfo=blobCache.create(uniqueId(),blob,base64),blobCache.add(blobInfo),resolve({image:img,blobInfo:blobInfo})})},function(err){reject(err)}))):(base64=Conversions.parseDataUri(img.src).data,blobInfo=blobCache.findFirst(function(cachedBlobInfo){return cachedBlobInfo.base64()===base64}),void(blobInfo?resolve({image:img,blobInfo:blobInfo}):Conversions.uriToBlob(img.src).then(function(blob){blobInfo=blobCache.create(uniqueId(),blob,base64),blobCache.add(blobInfo),resolve({image:img,blobInfo:blobInfo})},function(err){reject(err)})))}var each=tinymce.each,BlobCache=tinymce.file.BlobCache,Conversions=tinymce.file.Conversions,Uuid=tinymce.util.Uuid,DOM=tinymce.DOM,count=0,uniqueId=function(prefix){return(prefix||"blobid")+count++};tinymce.PluginManager.add("blobupload",function(ed,url){function findMarker(marker){var found;return each(ed.dom.select("img[src]"),function(image){if(image.src==marker.src)return found=image,!1}),found}function removeMarker(marker){each(ed.dom.select("img[src]"),function(image){if(image.src==marker.src){ed.selection.select(image),ed.execCommand("mceRemoveNode");var node=ed.selection.getNode();"P"==node.nodeName&&ed.dom.isEmpty(node)&&ed.dom.add(node,"br",{"data-mce-bogus":1})}})}function processImages(images){var cachedPromises={},promises=tinymce.map(images,function(img){var newPromise;return cachedPromises[img.src]?new Promise(function(resolve){cachedPromises[img.src].then(function(imageInfo){return"string"==typeof imageInfo?imageInfo:void resolve({image:img,blobInfo:imageInfo.blobInfo})})}):(newPromise=new Promise(function(resolve,reject){imageToBlobInfo(BlobCache,img,resolve,reject)}).then(function(result){return delete cachedPromises[result.image.src],result}).catch(function(error){return delete cachedPromises[img.src],error}),cachedPromises[img.src]=newPromise,newPromise)});return Promise.all(promises)}function uploadPastedImage(marker,blobInfo){return new Promise(function(resolve,reject){if(!uploaders.length)return removeMarker(marker),resolve();var html="<p>"+ed.getLang("upload.name_description","Please supply a name for this file")+'</p><div class="mceModalRow">   <label for="'+ed.id+'_blob_input">'+ed.getLang("dlg.name","Name")+'</label>   <div class="mceModalControl mceModalControlAppend">       <input type="text" id="'+ed.id+'_blob_input" autofocus />       <span role="presentation"></span>   </div></div>',win=ed.windowManager.open({title:ed.getLang("dlg.name","Name"),content:html,size:"mce-modal-landscape-small",buttons:[{title:ed.getLang("cancel","Cancel"),id:"cancel"},{title:ed.getLang("submit","Submit"),id:"submit",onclick:function(e){var filename=DOM.getValue(ed.id+"_blob_input");if(!filename)return removeMarker(marker),resolve();if(filename=filename.replace(/[\+\\\/\?\#%&<>"\'=\[\]\{\},;@\^\(\)£€$~]/g,""),/\.(php([0-9]*)|phtml|pl|py|jsp|asp|htm|html|shtml|sh|cgi)\b/i.test(filename))return ed.windowManager.alert(ed.getLang("upload.file_extension_error","File type not supported")),removeMarker(marker),resolve();var url,uploader;if(each(uploaders,function(instance){if(!url&&(url=instance.getUploadURL({name:blobInfo.filename()})))return uploader=instance,!1}),!url)return removeMarker(marker),resolve();var props={method:"upload",id:Uuid.uuid("wf_"),inline:1,name:filename,url:url+"&"+ed.settings.query},images=tinymce.grep(ed.dom.select("img[src]"),function(image){return image.src==marker.src});ed.setProgressState(!0),uploadHandler(props,blobInfo,function(data){data.marker=images[0];var elm=uploader.insertUploadedFile(data);return elm&&(ed.undoManager.add(),ed.dom.replace(elm,images[0]),ed.selection.select(elm)),ed.setProgressState(!1),win.close(),resolve()},function(error){return ed.windowManager.alert(error),ed.setProgressState(!1),resolve()},function(){})},classes:"primary"}],open:function(){DOM.select("input + span",this.elm)[0].innerText="."+getImageExtension(blobInfo.filename()),window.setTimeout(function(){DOM.get(ed.id+"_blob_input").focus()},10)},close:function(){return removeMarker(marker),resolve()}})})}var uploaders=[];ed.onPreInit.add(function(){each(ed.plugins,function(plg,name){if(tinymce.is(plg.getUploadConfig,"function")){var data=plg.getUploadConfig();data.inline&&data.filetypes&&uploaders.push(plg)}})}),ed.onInit.add(function(){ed.plugins.clipboard&&ed.onPasteBeforeInsert.add(function(ed,o){var transparentSrc="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7",node=ed.dom.create("div",0,o.content),images=tinymce.grep(ed.dom.select("img[src]",node),function(img){var src=img.getAttribute("src");return!img.hasAttribute("data-mce-bogus")&&(!img.hasAttribute("data-mce-placeholder")&&(!img.hasAttribute("data-mce-upload-marker")&&(!(!src||src==transparentSrc)&&(0===src.indexOf("blob:")||0===src.indexOf("data:")))))});if(images.length){var promises=[];processImages(images).then(function(result){each(result,function(item){"string"!=typeof item&&(ed.selection.select(findMarker(item.image)),ed.selection.scrollIntoView(),promises.push(uploadPastedImage(item.image,item.blobInfo)))})}),Promise.all(promises).then()}})})})}();