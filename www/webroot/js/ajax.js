window.onload(getCount());

//function getRandomInt(min, max) {
//  return (Math.floor(Math.random() * (max - min)) + min)*1;
//}

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
    // Взяли новое значение "всего"
    //main_counter = document.getElementById('main_counter');
//    new_main_counter = main_counter.innerHTML;
    // Записываем кол-во читающих сейчас
    //now_reading.innerHTML = new_main_counter;
//     - old_main_counter;

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