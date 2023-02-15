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

    const username = document.querySelector('#username')?.value
    const password = document.querySelector('#password')?.value

    // Client Side Validation

    const data = {
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

        console.log(exception.data.errors)
    }
})
