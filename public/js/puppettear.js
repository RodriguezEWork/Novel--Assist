const puppeteer = require('puppeteer');
var mysql = require('mysql');
var numero;

var con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "root",
    database: "novelread"
});

recorridoPage();

async function recorridoPage() {
    const browser = await puppeteer.launch({ headless: false });

    const page = await browser.newPage();

    await page.goto('https://es.mtlnovel.com/special-fondness/chapter-list/');
    const titulaso = await page.evaluate(() => {
        const titulos = document.querySelectorAll('.ch-list p .ch-link');

        const Elementos = [];
        for (let titulo of titulos) {
            Elementos.push(titulo.href);
        }

        return Elementos;
    })

    const textosGenerales = [];
    for (let entradas of titulaso) {
        await page.goto(entradas);
        await page.waitForTimeout(1000);
        await page.waitForSelector('.container');

        const textoGeneral = await page.evaluate(() => {

            const texto = document.querySelectorAll('.fontsize-16 p');

            const Elementos2 = [];
            for (let textoFijo of texto) {
                if (textoFijo.innerText != '') {
                    Elementos2.push(textoFijo.innerText);
                }
            }

            return Elementos2;
        });

        textosGenerales.push(textoGeneral);

    }

    await page.waitForTimeout(2000);
    await page.screenshot({ path: 'lafoto.jpg' });

    await browser.close();

    numero = titulaso.length;
    for (let i = 0; i <= textosGenerales.length; i++) {
        uniendo(textosGenerales[i])
        numero--;
    }

};

function uniendo($arreglo) {

    let titulaso = $arreglo;

    let texto = titulaso.join('\n');

    subir(texto);
}

function subir(texto) {

    var sql = "INSERT INTO capitulos (titulo, numero, marcado, capitulo, id_Novelas) VALUES ?";
    var values = [
        ['Capitulo: ', numero, false, texto, '15'],
    ];
    con.query(sql, [values], function(err, result) {
        if (err) throw err;
        console.log("1 record inserted");
    });
}