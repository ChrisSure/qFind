import Base from "../Base";

const authValidation = (authStore) => {
    const fieldEmail = "email";
    const fieldPassword = "email";

    let validation = new Base();
    validation.isRequire(fieldEmail, authStore.email);
    validation.email(fieldEmail, authStore.email);
    validation.isString(fieldEmail, authStore.email);
    validation.minString(fieldEmail, authStore.email, 3);

    validation.isRequire(fieldPassword, authStore.password);
    validation.isString(fieldPassword, authStore.password);
    validation.minString(fieldPassword, authStore.password, 2);

    return validation.errors;
}

export default authValidation;