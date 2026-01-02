import React, { createContext, useContext, useState, useEffect } from 'react';

interface ThemeContextType {
  themeColor: string;
  setThemeColor: (color: string) => void;
  loadTheme: () => Promise<void>;
}

const ThemeContext = createContext<ThemeContextType | undefined>(undefined);

export const ThemeProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [themeColor, setThemeColor] = useState('indigo');

  // Load theme from API on mount
  const loadTheme = async () => {
    try {
      const response = await fetch('./Backend/api/settings.php?action=theme');
      if (response.ok) {
        const data = await response.json();
        if (data.themeColor) {
          setThemeColor(data.themeColor);
          applyThemeColor(data.themeColor);
        }
      }
    } catch (error) {
      console.error('Failed to load theme:', error);
    }
  };

  useEffect(() => {
    loadTheme();
  }, []);

  const applyThemeColor = (color: string) => {
    const colorMap: Record<string, { light: string; dark: string; text: string }> = {
      indigo: { light: 'rgb(199, 210, 254)', dark: 'rgb(79, 70, 229)', text: 'rgb(79, 70, 229)' },
      rose: { light: 'rgb(254, 205, 211)', dark: 'rgb(244, 63, 94)', text: 'rgb(244, 63, 94)' },
      emerald: { light: 'rgb(209, 250, 229)', dark: 'rgb(16, 185, 129)', text: 'rgb(16, 185, 129)' },
      amber: { light: 'rgb(254, 243, 199)', dark: 'rgb(245, 158, 11)', text: 'rgb(245, 158, 11)' },
      slate: { light: 'rgb(226, 232, 240)', dark: 'rgb(30, 41, 59)', text: 'rgb(30, 41, 59)' },
    };

    const colors = colorMap[color] || colorMap.indigo;
    document.documentElement.style.setProperty('--color-primary-light', colors.light);
    document.documentElement.style.setProperty('--color-primary-dark', colors.dark);
    document.documentElement.style.setProperty('--color-primary', colors.text);
  };

  const updateTheme = (color: string) => {
    setThemeColor(color);
    applyThemeColor(color);
  };

  return (
    <ThemeContext.Provider value={{ themeColor, setThemeColor: updateTheme, loadTheme }}>
      {children}
    </ThemeContext.Provider>
  );
};

export const useTheme = () => {
  const context = useContext(ThemeContext);
  if (!context) {
    throw new Error('useTheme must be used within ThemeProvider');
  }
  return context;
};
