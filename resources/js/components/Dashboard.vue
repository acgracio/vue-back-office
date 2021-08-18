<template>
    <div class="container">
        <div class="row justify-content-center">
            <not-found></not-found>
        </div>
    </div>
</template>

<script>
import NotFound from "./NotFound";

export default {
    mounted() {
        console.log("Component mounted.");
    },
    components: {
        "not-found": NotFound
    }
};
</script>
