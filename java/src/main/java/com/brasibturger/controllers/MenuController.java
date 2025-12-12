package com.brasibturger.controllers;

import com.brasibturger.dtos.MenuDTO;
import com.brasibturger.services.MenuService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/menus")
@RequiredArgsConstructor
public class MenuController {
    
    private final MenuService menuService;
    
    @GetMapping
    public ResponseEntity<List<MenuDTO>> getAllMenus() {
        return ResponseEntity.ok(menuService.getAllMenus());
    }
    
    @GetMapping("/actifs")
    public ResponseEntity<List<MenuDTO>> getActiveMenus() {
        return ResponseEntity.ok(menuService.getActiveMenus());
    }
    
    @GetMapping("/{id}")
    public ResponseEntity<MenuDTO> getMenuById(@PathVariable Long id) {
        return ResponseEntity.ok(menuService.getMenuById(id));
    }
    
    @GetMapping("/search")
    public ResponseEntity<MenuDTO> searchMenuByName(@RequestParam String name) {
        return ResponseEntity.ok(menuService.searchMenuByName(name));
    }
    
    @PostMapping
    public ResponseEntity<MenuDTO> createMenu(@Valid @RequestBody MenuDTO menuDTO) {
        return ResponseEntity.status(HttpStatus.CREATED).body(menuService.createMenu(menuDTO));
    }
    
    @PutMapping("/{id}")
    public ResponseEntity<MenuDTO> updateMenu(
            @PathVariable Long id,
            @Valid @RequestBody MenuDTO menuDTO) {
        return ResponseEntity.ok(menuService.updateMenu(id, menuDTO));
    }
    
    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deleteMenu(@PathVariable Long id) {
        menuService.deleteMenu(id);
        return ResponseEntity.noContent().build();
    }
}
