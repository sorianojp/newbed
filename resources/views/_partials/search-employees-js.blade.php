<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('employees', () => ({
            search: '',
            employee: {},
            employees: [],
            init() {
                this.getEmployees();
            },
            getEmployees() {
                if (this.search.length >= 2) {
                    console.log(this.search)

                    axios.get('/search-employees', {
                        params: {
                            search: this.search,
                        }
                    }).then(response => {
                        this.employees = response.data
                    })
                } else {
                    this.employees = [];
                }
            },
            selectEmployee(employee) {
                this.search = employee.full_name
                this.employee = employee
                this.employees = []
            }
        }))
    })
</script>
