const form = document.querySelector('.login-form')

const http = {
    async request(url, options = {}) {
        const response = await fetch(url, options)
        const data = await response.json()

        if (!response.ok) {
            throw { status: response.status, data }
        }

        return { status: response.status, data }
    },
    async get(url) {
        return this.request(url)
    },
    async post(url, data = {}) {
        return this.request(url, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
    }
}

form?.addEventListener('submit', async event => {
    event.preventDefault()

    const csrfToken = document.querySelector('#csrfToken')?.value
    const username = document.querySelector('#username')?.value
    const password = document.querySelector('#password')?.value

    // Client Side Validation

    const data = {
        csrfToken,
        username,
        password
    }

    try {
        const response = await http.post('/login', data)
        window.location.replace('/dashboard')
    } catch (exception) {
        if (exception.status !== 422) {
            return
        }

        clearErrors()
        handleErrors(exception.data.errors)
    }
})

function handleErrors(errors) {
    // for (let field in errors) {
    //     const messages = errors[field]

    //     console.log(field, messages)
    // }

    for (let [field, messages] of Object.entries(errors)) {
        const element =
            document.querySelector(`#${field}`) ?? document.querySelector('form')
        element.insertAdjacentHTML(
            'beforebegin',
            `<div class="error">${messages[0]}</div>`
        )
    }
}

function clearErrors() {
    document.querySelectorAll('.error').forEach(element => {
        element.remove()
    })
}
