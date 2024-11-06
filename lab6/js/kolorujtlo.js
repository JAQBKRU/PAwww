var computed = false;
var decimal = 0;

function convert(entryform, from, to)
{
    convertfrom = from.selectedIndex;
    convertto = to.selectedIndex;
    entryform.display.value = (entryform.input.value * from[convertfrom].value / to[convertto].value);

    /*DODANE*/
    changeBackground(getRandomColor());
    /*DODANE*/
}

function addChar(input, character)
{
    if ((character == "." && decimal == "0") || character != ".")
    {
        (input.value == "" || input.value == "0") ? input.value = character : input.value += character
        convert(input.form, input.form.measure1, input.form.measure2)
        computed = true;
        if (character == ".")
        {
            decimal = 1;
        }
    }
}

function openVothcom()
{
    window.open("", "Display window", "toolbar=no,directories=no,menubar=no");
}

function clear(form)
{
    form.input.value = 0;
    form.display.value = 0;
    decimal=0;
}

function changeBackground(hexNumber)
{
    //document.body.style.backgroundColor = hexNumber;
    document.getElementById("data").style.backgroundColor = hexNumber;
}

/*DODANE*/
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
/*DODANE*/