window.onload(getCount(), show_last_comment());

function publish_last_comment(){
    document.getElementById('last_comment_show').style.display = 'block';
    document.getElementById('last_comment_edit').style.display = 'none';
}

function show_last_comment(){
    setTimeout (publish_last_comment, 60000);
}

function randWD(n){  // [ 2 ] random words and digits
  return Math.random().toString(36).slice(2, 2 + Math.max(1, Math.min(n, 10)) );
}

function ad_style(id)
{
    price_id = 'ad_price_'+id;
    price = document.getElementById(price_id);
    new_price = price.innerHTML*0.9;
    price.innerHTML = new_price;
    document.getElementById(price_id).style.color = 'red';
    document.getElementById(price_id).style.fontSize = 'larger';
    coupon_id = 'ad_coupon_'+id;
    $("#"+coupon_id).fadeIn(1000);
    coupon_text = 'coupon_text_'+id;
    document.getElementById(coupon_text).innerHTML = randWD(5);
}

function remove_style(id)
{
    price_id = 'ad_price_'+id;
    price = document.getElementById(price_id);
    price.innerHTML = (price.innerHTML/9)*10;
    document.getElementById(price_id).style.color = 'black';
    document.getElementById(price_id).style.fontSize = 'inherit';
    coupon_id = 'ad_coupon_'+id;
    $("#"+coupon_id).fadeOut(5000);
}

function getCount(){
    // Взяли объект "всего" старое
    main_counter = document.getElementById('main_counter');
    old_main_counter = main_counter.innerHTML;
    // Взяли объект "сейчас" старое
    now_reading = document.getElementById('now_reading');
    // Взяли объект "news_id"
    news_id = document.getElementById('news_id');
    // Идем в базу
    forNewDom('/news/counter/'+news_id.innerHTML, 0, 'main_counter');

    setTimeout(getCount, 10000);
}


// Если loyout_flag = 0 - загружаем весь контект,
//                          если 1 - только контент
// result - id куда вставить результат
function forNewDom(path, loyout_flag = '0', result){
        loyout_flag='loyout_flag='+loyout_flag;
        request = new AjaxRequest();
        request.open("POST", path, false);
        request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        request.setRequestHeader("Content-length", loyout_flag.length);
        request.setRequestHeader("Connection", "close");
        request.onreadystatechange = function()
        {
            if (this.readyState == 4)
            {
                if (this.status == 200)
                {
                    if (this.responseText != null)
                    {
                        document.getElementById(result).innerHTML = this.responseText;
                    }
                    else alert("Ошибка AJAX: Данные не получены");
                }
                else alert( "Ошибка AJAX: " + this.statusText);
            }
        };
        request.send(loyout_flag);
}

function AjaxRequest()
{
    try
    {
        var request = new XMLHttpRequest();
    }
    catch(e1)
    {
        try
        {
            request = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch(e2)
        {
            try
            {
                request = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(e3)
            {
                request = false;
            }
        }
    }
    return request;
}