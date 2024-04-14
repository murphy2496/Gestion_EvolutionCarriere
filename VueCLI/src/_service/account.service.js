import Axios from "./caller.service";

// login
let onlogin = (dataFormat) => {
    return Axios.post('/ /login.php?action=login', dataFormat)
}

// Section
let allSection = ()=>{
    return Axios.get('/ /section.php?action=section')
}
let allUserSection = (donnees)=>{
    return Axios.post('/ /section.php?action=user_section', donnees)
}
let countSection = (donnees)=>{
    return Axios.post('/ /section.php?action=nombre_section', donnees)
}
let setSection = (donnees)=>{
    return Axios.post('/ /section.php?action=addSec', donnees)
}
let AgentSection = ()=>{
    return Axios.get('/ /section.php?action=agent_section')
}

// nombre
let allRowCount = (donnees)=>{
    return Axios.post('/ /nombre.php?action=compter', donnees)
}

// chart
let getNbChart = ()=>{
    return Axios.get('/ /chart.php?action=nb')
}
let getNbSection = (donnees)=>{
    return Axios.post('/ /chart.php?action=nbSec',donnees)
}


// avancement
let allAvenantComplet = (donnees)=>{
    return Axios.post('/menu 1/avancement.php?action=tout', donnees)
}
let allAvenant6M = (donnees)=>{
    return Axios.post('/menu 1/avancement.php?action=sixM', donnees)
}
let allAvenantTard = (donnees)=>{
    return Axios.post('/menu 1/avancement.php?action=tard', donnees)
}

// contrat
let allContratComplet = (donnees)=>{
    return Axios.post('/menu 1/contrat.php?action=tout', donnees)
}
let allContrat6M = (donnees)=>{
    return Axios.post('/menu 1/contrat.php?action=sixM', donnees)
}
let allContratTard = (donnees)=>{
    return Axios.post('/menu 1/contrat.php?action=tard', donnees)
}

// retraite
let allRetraite1A = (donnees)=>{
    return Axios.post('/menu 1/retraite.php?action=1ans', donnees)
}
let allRetraiteTard = (donnees)=>{
    return Axios.post('/menu 1/retraite.php?action=tard', donnees)
}

// recherche entre deux date
let rechercheDateAvenant = (donnees)=>{
    return Axios.post('/menu 1/entre_deux_date.php?action=avenant', donnees)
}
let rechercheDateContrat = (donnees)=>{
    return Axios.post('/menu 1/entre_deux_date.php?action=contrat', donnees)
}
let rechercheDateRetraite = (donnees)=>{
    return Axios.post('/menu 1/entre_deux_date.php?action=retraite', donnees)
}


// profile
let getUserEdit = (donnees)=>{
    return Axios.post('/menu 2/profile.php?action=user', donnees)
}
let setUserEdit = (donnees)=>{
    return Axios.post('/menu 2/profile.php?action=edit', donnees)
}

// importer
let addImport = (donnees)=>{
    return Axios.post('/menu 2/importer.php?action=add', donnees)
}
let viderTable = ()=>{
    return Axios.post('/menu 2/importer.php?action=vider')
}
let dernierImport = ()=>{
    return Axios.get('/menu 2/importer.php?action=date_import')
}

// user
let onAllUser = () => {
    return Axios.get('/menu 2/user.php?action=user')
}
let setUser = (donnees) => {
    return Axios.post('/menu 2/user.php?action=add',donnees)
}
let deleteUser = (donnees) => {
    return Axios.post('/menu 2/user.php?action=delete',donnees)
}
let editUser = (donnees) => {
    return Axios.post('/menu 2/user.php?action=edit',donnees)
}


export const accountService = {
    onlogin,
    onAllUser,setUser,deleteUser,editUser,
    allSection,allUserSection, countSection, setSection, AgentSection,
    allAvenantComplet,allAvenant6M,allAvenantTard,
    allContratComplet,allContrat6M,allContratTard,
    allRetraite1A,allRetraiteTard,
    addImport,viderTable,dernierImport,
    allRowCount,getUserEdit,setUserEdit,
    rechercheDateAvenant,rechercheDateContrat,rechercheDateRetraite,
    getNbChart,getNbSection
}