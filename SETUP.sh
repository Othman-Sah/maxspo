#!/bin/bash
# NEEDSPORT Pro - Setup Script
# Run this to set up the PHP application

echo "🏆 NEEDSPORT Pro - Setup"
echo "========================"

# Create necessary directories
mkdir -p /public/uploads
mkdir -p /logs

# Set permissions
chmod 755 /public/uploads
chmod 755 /logs

echo "✓ Directories created"

# Database setup instructions
echo ""
echo "📋 Next Steps:"
echo "============="
echo ""
echo "1. Create MySQL Database:"
echo "   mysql -u root -p"
echo "   CREATE DATABASE needsport_pro;"
echo ""
echo "2. Update database credentials in /config/config.php"
echo ""
echo "3. Access application at:"
echo "   http://localhost/maxsporti/"
echo ""
echo "4. Login with:"
echo "   Email: admin@needsport.ma"
echo "   Password: password"
echo ""
echo "✓ Setup complete!"
