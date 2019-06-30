const APP_ID = 'fd2678a168142c0b422c2a90ee25088e'
const searchButton = document.querySelector('.menu--search')
const locationButton = document.querySelector('.menu--loc')
const input = document.querySelector('.menu__city')
const city = document.querySelector('.name')
const icon = document.querySelector('.icon')
const temp = document.querySelector('.temp')
const h1 = document.querySelector('.h1')


const getWeatherForLocation = () => {
    const searchForCurrentLocation = options => {
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, options)
        })}

    searchForCurrentLocation()
        .then((position) => {
            const url = `https://api.openweathermap.org/data/2.5/weather?lat=${ position.coords.latitude }&lon=${ position.coords.longitude }&units=metric&appid=${ APP_ID }`
            h1.innerHTML = `Météo actuelle pour votre position`
            locationButton.style.display = `none`
            getWeather(url)
        })
        .catch((err) => {
            console.error(err.message)
            alert(err.message)
        })
}

getWeatherForLocation()
locationButton.addEventListener('click', getWeatherForLocation)

searchButton.addEventListener('click', e => {
    const cityName = input.value
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${ cityName }&units=metric&appid=${ APP_ID }`
    h1.innerHTML = 'Météo actuelle de la ville recherchée'
    locationButton.innerHTML = `Obtenir la météo pour votre position`
    locationButton.style.display = `block`
    getWeather(url)
})

input.addEventListener('keyup', e => {
    e.keyCode === 13 && searchButton.click()
})

const getWeather = url => (
    fetch(url).then(response => {
        if (response.status !== 200) {
            console.error(`Status Code: ${ response.status }`)
        }
        console.log(url)
        return response.json()
    }).then(data => {
        console.log(data)
        const { weather, main } = data
        city.innerHTML = `${ data.name}`
        icon.innerHTML = `<img src= 'https://openweathermap.org/img/w/${ weather[0].icon }.png'>`
        temp.innerHTML = `${ Math.round(main.temp) }&deg;C`
    }))



