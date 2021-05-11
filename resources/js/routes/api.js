const apiUrl = "http://127.0.0.1:8000/api";
export default {
    auth: {
        login        : `${apiUrl}/login`,
        register     : `${apiUrl}/register`,

        refreshToken : `${apiUrl}/refresh-token`,
        me           : `${apiUrl}/me`,
        logout       : `${apiUrl}/logout`,
    },

    grade        : `${apiUrl}/grade`,
    employee     : `${apiUrl}/employee`,
    salary       : `${apiUrl}/salary`,
    transaction  : `${apiUrl}/transaction`,
};
