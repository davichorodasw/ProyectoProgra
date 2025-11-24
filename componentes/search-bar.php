<div class="search-section">
    <div class="search-container">
        <form action="<?php echo isset($searchAction)
            ? $searchAction
            : "#"; ?>" method="GET" class="search-form">
            <div class="search-input-group">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="<?php echo isset($searchPlaceholder)
                        ? $searchPlaceholder
                        : "Buscar..."; ?>" 
                    value="<?php echo isset($_GET["q"])
                        ? htmlspecialchars($_GET["q"])
                        : ""; ?>"
                    class="search-input"
                >
                <button type="submit" class="search-button">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                    Buscar
                </button>
            </div>
        </form>
    </div>
</div>