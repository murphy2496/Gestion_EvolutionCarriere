<template>
    <div class="wrapper">
        <div class="body-overlay"></div>
        <nav id="sidebar">
            <SideBar />
        </nav>
        <div id="content">
            <HeaderBar class="shadow" />
            <div class="main-content  pt-0 p-0 mb-0">
                <div>
                    <router-view />
                </div>
            </div>
            
        </div>
        
    </div>
</template>

<script>
import SideBar from "@/components/SideBar.vue";
import HeaderBar from "@/components/HeaderBar.vue";
import FooterBar from "@/components/FootNav.vue";
export default {
    name: 'Layout',
    components: {
        SideBar, HeaderBar, FooterBar
    }
}
</script>