@echo on
for /r %%f in (*.obj *.tlog *.log *.pdb *.pch *.sdf *.res *.manifest *.lastbuildstate *.ipch *.exp *.ilk) do del %%f
