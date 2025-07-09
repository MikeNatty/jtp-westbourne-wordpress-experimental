import React, { createContext, useContext, useEffect, useState } from 'react';

interface Breakpoints {
  isMinimumTabletHorizontal: boolean;
}

// Define our breakpoint values (in pixels)
const BREAKPOINTS = {
  mobile: 480,
  tablet: 768,
  tabletHorizontal: 1024,
  desktop: 1280,
};

// Create the context
const ViewportContext = createContext<Breakpoints>({
  isMinimumTabletHorizontal: false,
});

export const ViewportProvider: React.FC<{ children: React.ReactNode }> = ({
  children,
}) => {
  const [viewport, setViewport] = useState<Breakpoints>({
    isMinimumTabletHorizontal: false,
  });

  useEffect(() => {
    // Create media query lists
    const minimumTabletHorizontalQuery = window.matchMedia(
      `(min-width: ${BREAKPOINTS.tabletHorizontal}px)`
    );

    const updateViewport = () => {
      setViewport({
        isMinimumTabletHorizontal: minimumTabletHorizontalQuery.matches,
      });
    };

    // Set initial viewport
    updateViewport();

    // Add event listeners
    minimumTabletHorizontalQuery.addEventListener('change', updateViewport);

    // Cleanup
    return () => {
      minimumTabletHorizontalQuery.removeEventListener(
        'change',
        updateViewport
      );
    };
  }, []);

  return (
    <ViewportContext.Provider value={viewport}>
      {children}
    </ViewportContext.Provider>
  );
};

// Custom hook to use the viewport context
export const useViewport = () => {
  const context = useContext(ViewportContext);

  if (context === undefined) {
    throw new Error('useViewport must be used within a ViewportProvider');
  }

  return context;
};
