<x-guest-layout>
    @include('layouts.landing_nav')



<!-- HEADER -->
<section class="bg-gradient-to-r from-blue-700 to-blue-500 text-white text-center py-12 px-4">
    <p class="text-sm opacity-90">
        Discover programs designed to advance your career and unlock your potential.
    </p>
    <h1 class="text-3xl md:text-4xl font-bold mt-2">Academic Programs</h1>
</section>

<!-- FILTER -->
<section class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row gap-4">
        <input type="text" placeholder="Search programs..."
            class="w-full md:w-1/2 p-3 rounded-lg border">

        <select class="p-3 rounded-lg border w-full md:w-1/4">
            <option>All Categories</option>
        </select>

        <select class="p-3 rounded-lg border w-full md:w-1/4">
            <option>All Level</option>
        </select>
    </div>
</section>

<!-- PROGRAMS -->
<section class="max-w-7xl mx-auto px-4 pb-10">
    <h2 class="text-xl font-semibold mb-4">Featured Programs</h2>

    <div id="cardContainer"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
    </div>

    <!-- PAGINATION -->
    <div class="flex justify-center mt-8 space-x-2" id="pagination"></div>
</section>

<script>
const programs = [
    {title:"Computer Science", students:245, price:"₱15,000/year"},
    {title:"Business Administration", students:312, price:"₱14,000/year"},
    {title:"Data Science", students:89, price:"₱18,000/year"},
    {title:"Information Technology", students:200, price:"₱16,000/year"},
    {title:"Education", students:150, price:"₱10,000/year"},
    {title:"Nursing", students:120, price:"₱20,000/year"},
    {title:"Engineering", students:180, price:"₱22,000/year"},
    {title:"Psychology", students:95, price:"₱13,000/year"},
    {title:"Architecture", students:60, price:"₱25,000/year"},
    {title:"Accounting", students:140, price:"₱17,000/year"},
];

const cardsPerPage = 5;
let currentPage = 1;

function displayCards() {
    const container = document.getElementById('cardContainer');
    container.innerHTML = "";

    const start = (currentPage - 1) * cardsPerPage;
    const end = start + cardsPerPage;

    const paginatedItems = programs.slice(start, end);

    paginatedItems.forEach(program => {
        container.innerHTML += `
            <div class="bg-white p-4 rounded-lg shadow space-y-3">
                <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded">Featured</span>

                <h3 class="font-semibold text-lg">${program.title}</h3>
                <p class="text-sm text-gray-500">
                    Lorem ipsum description for ${program.title}.
                </p>

                <div class="text-sm text-gray-600">
                    👨‍🎓 ${program.students} students
                </div>

                <div class="text-blue-600 font-semibold">
                    ${program.price}
                </div>

                <div class="flex gap-2">
                    <button class="bg-blue-600 text-white px-3 py-1 rounded w-full">
                        Apply Now
                    </button>
                    <button class="border px-3 py-1 rounded w-full">
                        Learn More
                    </button>
                </div>
            </div>
        `;
    });
}

function setupPagination() {
    const pageCount = Math.ceil(programs.length / cardsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = "";

    for (let i = 1; i <= pageCount; i++) {
        pagination.innerHTML += `
            <button onclick="changePage(${i})"
                class="px-3 py-1 rounded border ${i === currentPage ? 'bg-blue-600 text-white' : ''}">
                ${i}
            </button>
        `;
    }
}

function changePage(page) {
    currentPage = page;
    displayCards();
    setupPagination();
}

// INIT
displayCards();
setupPagination();
</script>

    
</x-guest-layout>