function replaceUrlParam(url, param, paramValue) {
    // console.log(url);
    var urlBasePart = url.split('?')[0];
    var urlQueryPart = url.split('?')[1];//get url part without params

    var newUrl = urlBasePart + '?';

    url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str,key,value) {
        //console.log(str+'--'+key+'--'+value+'-----');
        if(key == param){
            newUrl = newUrl + key+'='+paramValue+'&';
        }else{
            newUrl = newUrl + key+'='+value+'&';
        }
    });
    return newUrl;
}
