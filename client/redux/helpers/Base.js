export default class Base {

    errors = [];

    get errors() {
        return this.errors;
    }

    isRequire(field, value) {
        if (value === "" || !value) {
            this.errors.push(`Field ${field} can not be empty.`);
        }
    }

    email(field, value) {
        const regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!regex.test(value)) {
            this.errors.push(`Field ${field} is not valid email.`);
        }
    }

    maxString(field, value, count) {
        if (value.length > count) {
            this.errors.push(`Field ${field} can not be more ${count} characters.`);
        }
    }

    minString(field, value, count) {
        if (value.length < count) {
            this.errors.push(`Field ${field} can not be less ${count} characters.`);
        }
    }

    isString(field, value) {
        if (typeof value !== 'string') {
            this.errors.push(`Field ${field} should be string.`);
        }
    }

    isNumber(value) {
        if (!value.isInteger) {
            this.errors.push(`Field ${field} should be string.`);
        }
    }

}