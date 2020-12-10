<template>
<v-app>
    <leftnavbar />
    <v-content>
        <v-container fluid>
            <v-layout>
                <transition name="slide" mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-layout>
        </v-container>
    </v-content>
    <footerbar />
</v-app>
</template>
<style>
.v-badge--overlap .v-badge__badge {
    top: 0px !important;
    right: 0px !important;
}

.width0 {
    min-width: 0px !important
}
</style>
<script>
import LeftNavBar from './leftnavbar.vue';
import FooterBar from './footerbar.vue';

export default {
    data: () => ({
      data:[]
    }),
    components: {
        'leftnavbar': LeftNavBar,
        'footerbar': FooterBar,
    }
}
</script>
