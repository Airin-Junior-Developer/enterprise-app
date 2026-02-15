
const fs = require('fs');
const path = 'c:\\Users\\tmgam\\Desktop\\enterprise-app\\enterprise-app\\resources\\js\\components\\RequestManager.vue';

try {
    let content = fs.readFileSync(path, 'utf8');
    
    // Check if we can find the exact string first
    const target = "currentUser?.position_name || 'My\r\n                                    Request'";
    const target2 = "currentUser?.position_name || 'My\n                                    Request'";
    
    if (content.includes(target)) {
        console.log("Found match with CRLF");
        content = content.replace(target, "currentUser?.position_name || 'My Request'");
    } else if (content.includes(target2)) {
        console.log("Found match with LF");
        content = content.replace(target2, "currentUser?.position_name || 'My Request'");
    } else {
        console.log("Exact match not found. Trying regex.");
        // Regex approach
        // Match: currentUser?.position_name || '
        // followed by any whitespace including newlines
        // followed by My Request'
        const regex = /currentUser\?\.position_name\s*\|\|\s*'[\s\r\n]+My\s+Request'/;
        
        if (regex.test(content)) {
            console.log("Found regex match");
            content = content.replace(regex, "currentUser?.position_name || 'My Request'");
        } else {
            console.log("No match found at all!");
            // print the lines around line 157
            const lines = content.split(/\r?\n/);
            // Assuming 1-based index 157 is array index 156
            console.log("Line 156:", lines[156]);
            console.log("Line 157:", lines[157]);
        }
    }
    
    fs.writeFileSync(path, content, 'utf8');
    console.log("File written");
    
} catch (e) {
    console.error(e);
}
