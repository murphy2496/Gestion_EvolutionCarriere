import axios from "axios";

const Axios = axios.create({
    baseURL: 'http://localhost/GestionEvolutionCarriere/API PHP/'
})
export default Axios