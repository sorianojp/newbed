<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('employees', () => ({
            search: '',
            employee: {},
            employees: [],
            init() {
                this.getEmployees();
                this.$watch('search', (value) => {
                    if (value.length >= 3) {
                        this.getEmployees();
                    } else {
                        this.employees = []
                    }
                })
            },
            getEmployees() {
                axios.get('/search-employees', {
                    params: {
                        search: this.search,
                    }
                }).then(response => {
                    this.employees = response.data
                })
            }
        }))
    })
</script>
