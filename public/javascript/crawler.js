const puppeteer = require('puppeteer');
const cheerio = require('cheerio');

(async function main (){
    try {
        //打開瀏覽器
        const browser = await puppeteer.launch({
            defaultViewport:{
                width: 1530,
                height: 800
            },
            headless:true
        });
        const page = await browser.newPage();
        //設定連線header
        page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36')
        
        //上系網
        await page.goto('https://dept.nknu.edu.tw/WE/zh/');

        //回傳系網html
        const pageData = await page.evaluate(() =>{
            return{
                html: document.documentElement.innerHTML
            }
        });

        //把html丟給cheerio
        const $ = cheerio.load(pageData.html);

        //尋寶
        const list = [];
            $('#mainnav').find('li.item-864 a').each(function (index, element) {
            list.push($(element).attr('href'));
        });

        //獲得當前url
        const Addnow = page.url();

        //獲得系網navbar裡各個鏈接
        const afterList = [];
        for ( i = 0 ; i < list.length ; i ++ ){
            const getAdd = String(list[i].substring(7,30));
            const mixAdd =  Addnow + getAdd;
            afterList.push(mixAdd);
        }

        //newpage
        const page2 = await browser.newPage();
        //去師資資訊
        await page2.goto(afterList[0]);

        const teacherpageData = await page2.evaluate(() =>{
            return{
                html: document.documentElement.innerHTML
            }
        });

        const $2 = cheerio.load(teacherpageData.html);

        const teacherList = [];
        $2('div.row').find('div#tab div.col-sm-6').each(function (index, element) {
            teacherList.push($(element).html());
        });

        console.log(teacherList);

        browser.close();

    } catch (error) {
        console.log(error)
    }
})();
